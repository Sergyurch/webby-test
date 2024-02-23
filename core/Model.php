<?php
	namespace Core;
	
	class Model {
		protected static $db_connection;
		
		public function __construct() {
			if (!self::$db_connection) {
				$host = DB_HOST;
				$dbName = DB_NAME;

				self::$db_connection = new \PDO("mysql:host=$host;dbname=$dbName", DB_USER, DB_PASS);
			}
		}
	}
