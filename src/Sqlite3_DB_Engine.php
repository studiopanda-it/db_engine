<?php

namespace StudioPanda;

abstract class Sqlite3_DB_Engine implements DB_Engine {

	use DB_Engine_Utils;

	protected static $connections = [];

	protected static function connection() {
		$class = get_called_class();
		if(empty(self::$connections[$class])) {
			if(!defined("$class::filename")) {
				throw new \Exception("Filename for $class is not defined.");
			}
			if(empty(self::$connections)) {
				register_shutdown_function([get_class(), "disconnectAll"]);
			}
			$encryption_key = defined("$class::encryption_key") ? constant("$class::encryption_key") : "";
			self::$connections[$class] = new \SQLite3(constant("$class::filename"), SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, $encryption_key);
			if(!self::$connections[$class]) {
				throw new \Exception("Connection for $class cannot be created.");
			}
		}
		return self::$connections[$class];
	}

	static function escape(string $string) : string {
		return \SQLite3::escapeString($string);
	}

	static function disconnectAll() : void {
		foreach(self::$connections as $connection) {
			$connection->close();
		}
		self::$connections = [];
	}

	static function disconnect() : void {
		$class = get_called_class();
		if(!empty(self::$connections[$class])) {
			self::$connections[$class]->close();
			unset(self::$connections[$class]);
		}
	}

	static function query(...$args) {
		return self::connection()->query(self::sanitize($args));
	}

	static function value(...$args) {
		return self::rowN($args)[0] ?? null;
	}

	static function row(...$args) {
		return self::query($args)->fetchArray(SQLITE3_ASSOC);
	}

	static function rowN(...$args) {
		return self::query($args)->fetchArray(SQLITE3_NUM);
	}

	static function values(...$args) {
		$handler = self::query($args);
		$values = [];
		while($tmp = $handler->fetchArray((SQLITE3_NUM))) {
			$values []= $tmp[0];
		}
		return $values;
	}

	static function rows(...$args) {
		$handler = self::query($args);
		while($rows[] = $handler->fetchArray(SQLITE3_ASSOC));
		return array_slice($rows, 0, -1);
	}

	static function rowsN(...$args) {
		$handler = self::query($args);
		while($rows[] = $handler->fetchArray((SQLITE3_NUM)));
		return array_slice($rows, 0, -1);
	}

	static function id(...$args) {
		self::query($args);
		return self::connection()->lastInsertRowId();
	}

}
