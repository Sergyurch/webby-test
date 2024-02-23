<?php
	namespace Project\Models;
	use \Core\Model;
	
	class User extends Model {
        public function getByEmail($email) {
            $sql = "SELECT * FROM users WHERE email = :email;";
            $stmt = self::$db_connection->prepare($sql);
            $stmt->execute([':email' => $email]);
            
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

		public function addNew($data) {
            $sql = "INSERT INTO users (email, password, name) VALUES (:email, :password, :name);";
            $stmt = self::$db_connection->prepare($sql);
            
            return $stmt->execute($data);
        }
	}
	