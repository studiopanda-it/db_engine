<?php

namespace StudioPanda;

trait DB_Engine_Utils {

	static function escape(string $string) : string {
		throw new \Exception("Escape function not implemented for ".get_class().".");
	}

	static function arrayToAssoc($index, array $array) : array {
		$return = [];
		foreach($array as $item) {
			$return[$item[$index]] = $item;
		}
		return $return;
	}

	static function assocArrayToInsert(array $array) : string {
		$columns = [];
		$rows = [];
		foreach($array as $key => $values) {
			$columns []= "`".self::escape($key)."`";
			if(!is_array($values)) {
				$values = [$values];
			}
			$i = 0;
			foreach($values as $value) {
				if(empty($rows[$i])) {
					$rows[$i] = [];
				}
				$rows[$i] []= "'".self::escape($value)."'";
				$i++;
			}
		}
		foreach($rows as $i => $value) {
			$rows[$i] = implode(",", $value);
		}
		return " (".implode(",", $columns).")VALUES(".implode("),(", $rows).") ";
	}

	static function assocArrayToUpdate(array $array) : string {
		$return = [];
		foreach($array as $key => $value) {
			$return []= "`".self::escape($key)."`='".self::escape($value)."'";
		}
		return " ".implode(",", $return)." ";
	}

	static function sanitize(...$args) : string {
		while((count($args) === 1) && is_array($args[0])) {
			$args = $args[0];
		}
		$return = [];
		$escape = false;
		foreach($args as $arg) {
			$return []= $escape ? "'".self::escape($arg)."'" : $arg;
			$escape = !$escape;
		}
		return " ".implode(" ", $return)." ";
	}

}
