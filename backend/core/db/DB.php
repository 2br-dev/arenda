<?php
//
require_once 'config.php';
//
class DB {
	const dbHost    = DB_HOST;
	const dbName    = DB_NAME;
	const dbUser    = DB_USER;
	const dbPass    = DB_PASS;
	const dbCharset = DB_CARSET;

	public static function connect() {
		$dsn = "mysql:host=" . self::dbHost . ";dbname=" . self::dbName . "; charset=" . self::dbCharset;
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
		];
		return new PDO ($dsn, self::dbUser, self::dbPass, $options);
	}
}