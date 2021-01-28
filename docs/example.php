<?php

require_once "vendor/autoload.php";

final class DB extends \StudioPanda\Mysqli_DB_Engine { // all parameters are optional and have defaults

	protected const
		host = "localhost",
		username = "username",
		passwd = "password",
		dbname = "database",
		port = 3306,
		socket = "/var/mysql/mysql.sock",
		transport_charset = "utf8",
		timezone = "Europe/Rome";

}



final class DBlite extends \StudioPanda\Sqlite_DB_Engine { // encryption_key parameter is optional

	protected const
		filename = "db.sqlite",
		encryption_key = "password";

}