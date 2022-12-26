
# DB_engine
## Convenient and secure PHP interface for mySQL/MariaDB & sqlite databases.

DB_engine is a PHP class that provides easy to use and secure access to mySQL/MariaDB & sqlite databases.
Requirements are:
- PHP 7.0+
- SQLite3 extension (for sqlite version)

DB_engine is available on Packagist, and can be installed via Composer. Just add this line to your composer.json file:
```
"studiopanda/db_engine": "^1.0"
```
or run
```
composer require studiopanda/db_engine
```

DB_engine is designed with static methods. It can be used as
```
my_database::query("SELECT name FROM users WHERE username=", $username);
```
and
```
$result = my_database::value("SELECT name FROM users WHERE username=", $username);
```
syntax.

DB_engine has an handy system that can take an array of strings and concatenate them adding the proper escape and quotes. For example:
```
query("SELECT * WHERE surname=", "O'connor", " AND (age>", 24, " OR age<", 35, ")")
```
will be compiled into:
```
query("SELECT * WHERE name='O\'connor' AND (age>'24' OR age<'35')")
```
Please note that in SQL languages there is no strict typing, so treating a number as a string has no downsides and this approach guarantee worry free security from both code bugs and malicious injections.

The main DB_engine’s method is `query`, which will perform the query. Any other methods will accept the same query format parameters and return a specific value:
```
value		// returns a string containing the first value of the first row
values		// returns an array of strings representing the first values of every row
row			// returns an associative array representing the first row
rowN		// returns an array representing the first row
rows		// returns an array containing every row represented as an associative array
rowsN		// returns an array containing every row represented as an array
id			// returns the last insertion id
```

Configuration example: docs/example.php

There are some other utility methods:
```
escape				// returns a string escaped for the database context
sanitize			// returns a string sanitized for the database using the same syntax of query
assocArrayToInsert	// returns a string containing the fields and values of an associative array formatted for an INSERT query
assocArrayToUpdate	// returns a string containing the fields and values of an associative array formatted for an UPDATE query
arrayToAssoc		// returns an associative array from an array
```
