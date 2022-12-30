<?php

namespace StudioPanda;

abstract class Mysqli_DB_Engine implements DB_Engine {

	use DB_Engine_Utils;

	protected static $connections = [];

	protected const defaults = [
			"host" => ["ini_get", ["mysqli.default_host"]],
			"username" => ["ini_get", ["mysqli.default_user"]],
			"passwd" => ["ini_get", ["mysqli.default_pw"]],
			"dbname" => "",
			"port" => ["ini_get", ["mysqli.default_port"]],
			"socket" => ["ini_get", ["mysqli.default_socket"]],
			"transport_charset" => "utf8",
			"timezone" => ["date_default_timezone_get", []]
		];

	protected static function connection() {
		$class = get_called_class();
		if(empty(self::$connections[$class])) {
			if(empty(self::$connections)) {
				register_shutdown_function([get_class(), "disconnectAll"]);
			}
			$settings = [];
			foreach(self::defaults as $id => $default) {
				if(defined("$class::$id")) {
					$settings[$id] = constant("$class::$id");
				} else {
					$settings[$id] = is_array($default) ? call_user_func_array($default[0], $default[1]) : $default;
				}
			}
			self::$connections[$class] = @mysqli_connect($settings["host"], $settings["username"], $settings["passwd"], $settings["dbname"], $settings["port"], $settings["socket"]);
			if(!self::$connections[$class]) {
				throw new \Exception("Connection for $class cannot be created.");
			}
			@mysqli_set_charset(self::$connections[$class], $settings["transport_charset"]);
			self::query("SET time_zone=", $settings["timezone"]);
		}
		return self::$connections[$class];
	}

	static function database(string $db) {
		return @mysqli_select_db(self::connection(), $db);
	}

	static function escape(string $string) : string {
		return @mysqli_real_escape_string(self::connection(), $string);
	}

	static function disconnectAll() : void {
		foreach(self::$connections as $connection) {
			@mysqli_close($connection);
		}
		self::$connections = [];
	}

	static function disconnect() : void {
		$class = get_called_class();
		if(!empty(self::$connections[$class])) {
			@mysqli_close(self::$connections[$class]);
			unset(self::$connections[$class]);
		}
	}

	static function query(...$args) {
		return @mysqli_query(self::connection(), self::sanitize($args));
	}

	static function value(...$args) {
		return self::rowN($args)[0] ?? null;
	}

	static function row(...$args) {
		return @mysqli_fetch_assoc(self::query($args));
	}

	static function rowN(...$args) {
		return @mysqli_fetch_row(self::query($args));
	}

	static function values(...$args) {
		$handler = self::query($args);
		$values = [];
		while($tmp = @mysqli_fetch_row($handler)) {
			$values []= $tmp[0];
		}
		return $values;
	}

	static function rows(...$args) {
		$handler = self::query($args);
		while($rows[] = @mysqli_fetch_assoc($handler));
		return array_slice($rows, 0, -1);
	}

	static function rowsN(...$args) {
		$handler = self::query($args);
		while($rows[] = @mysqli_fetch_row($handler));
		return array_slice($rows, 0, -1);
	}

	static function id(...$args) {
		self::query($args);
		return @mysqli_insert_id(self::connection());
	}

}
