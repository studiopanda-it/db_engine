<?php

namespace StudioPanda;

interface DB_Engine {

	static function assocArrayToInsert(array $array) : string;

	static function assocArrayToUpdate(array $array) : string;

	static function sanitize(...$args) : string;

	static function escape(string $string) : string;

	static function disconnectAll() : void;

	static function disconnect() : void;

	static function query(...$args);

	static function value(...$args);

	static function row(...$args);

	static function rowN(...$args);

	static function values(...$args);

	static function rows(...$args);

	static function rowsN(...$args);

	static function id(...$args);

}