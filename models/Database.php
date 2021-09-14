<?php

namespace models;
use PDO;

class Database
{
	private static $HOST = 'localhost';
	private static $USER = 'root';
	private static $PASSWORD = '';

	private static function connect($db) {
		try {
			$pdo = new PDO('mysql:host='.self::$HOST.';dbname='.$db,self::$USER,self::$PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		} catch(Exception $e) {
			echo 'Error connecting to database';
		}
		return $pdo;
	}

	public static function languages() {
		return self::connect('languages');
	}

	public static function phrases() {
		return self::connect('phrases');
	}
}

?>