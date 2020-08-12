<?php
namespace App;
class Database
{
	private static $instance = null;
	private static $db;
	private function __construct() {}
	public static function makeConnection() : \PDO
	{
        $config = require "config.php";
        self::$db = $config['db'];
		try {
			self::$instance = new \PDO(
				"mysql:host=" . self::$db['host'] . ";dbname=" . self::$db['name'], 
				self::$db['username'], 
				self::$db['password']
			);
		} catch(\Exception $ex) {
			echo "ERROR: {$ex->getMessage()}"; 
		}
		return self::$instance;
	}
	public static function getInstance()
	{
		if(self::$instance === null) {
			self::$instance = new static();
		}
		return self::$instance;
	}
}