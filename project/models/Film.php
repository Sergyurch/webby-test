<?php
	namespace Project\Models;
	use \Core\Model;
	
	class Film extends Model {
		public function getAll() {
            $sql = "SELECT id, title FROM films ORDER BY title;";
            $result = self::$db_connection->query($sql);

			return $result->fetchAll(\PDO::FETCH_ASSOC);
        }

		public function getById($id) {
            $sql = "
					SELECT 
						f.*,
						GROUP_CONCAT(DISTINCT CONCAT(a.firstname, ' ', a.lastname) SEPARATOR ', ') AS actors,
						GROUP_CONCAT(DISTINCT fr.name SEPARATOR ', ') AS formats
					FROM films AS f
					JOIN films_actors AS fa ON fa.film_id = f.id
					JOIN actors AS a ON a.id = fa.actor_id
					JOIN films_formats AS ff ON ff.film_id = f.id
					JOIN formats AS fr ON fr.id = ff.format_id
					WHERE f.id = :id
					GROUP BY f.id;
			";
			$stmt = self::$db_connection->prepare($sql);
			$stmt->execute([':id' => $id]);
            
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

		public function checkExists($title) {
			$sql = "SELECT count(*) AS count FROM films WHERE title LIKE :title";
			$stmt = self::$db_connection->prepare($sql);
			$stmt->execute([':title' => "$title"]);

			return ($stmt->fetch(\PDO::FETCH_ASSOC))['count'] > 0;
		}

		public function add($data) {
			extract($data);
			$db = self::$db_connection;

			try {
				$db->beginTransaction();

				$sql = "INSERT INTO films (title, year) VALUES (:title, :year);";
				$params = [
					':title' => $title,
					':year' => $year
				];
				$stmt = $db->prepare($sql);
				$stmt->execute($params);
				$filmId = $db->lastInsertId();
				$actorsIds = (new Actor)->getActorsIdsByFullnames($actors);
				
				$sql = "INSERT INTO films_actors (film_id, actor_id) VALUES ";
				$params = [];
				$valuesSQL = [];

				foreach($actorsIds as $key => $id) {
					$valuesSQL[] = "($filmId, :id_$key)";
					$params[":id_$key"] = $id; 
				}

				$sql .= implode(',', $valuesSQL);
				$stmt = $db->prepare($sql);
				$stmt->execute($params);

				$sql = "INSERT INTO films_formats (film_id, format_id) VALUES ";
				$params = [];
				$valuesSQL = [];

				foreach($formats as $key => $format) {
					$valuesSQL[] = "($filmId, (SELECT id FROM formats WHERE name = :format_$key))";
					$params[":format_$key"] = $format; 
				}

				$sql .= implode(',', $valuesSQL);
				$stmt = $db->prepare($sql);
				$stmt->execute($params);

				$db->commit();

				return true;
			} catch(\PDOException $e) {
				$db->rollBack();
				
				return false;
			}
		}

		public function deleteMany($films) {
			$params = [];
			$preparedIdList = [];	

			foreach($films as $key => $id) {
				$params[":film_$key"] = $id;
				$preparedIdList[] = ":film_$key";
			}

			$preparedIdList = implode(',', $preparedIdList);

			try {
				$sql = "DELETE FROM films WHERE id IN ($preparedIdList);";
				$stmt = self::$db_connection->prepare($sql);
				
				return $stmt->execute($params);
			} catch(\PDOException $e) {
				return false;
			}
		}

		public function search($searchBy, $value) {
			if ($value === '') {
				$sqlPart = '';
				$params = [];
			} elseif ($searchBy === 'title') {
				$sqlPart = " WHERE title LIKE :value ";
				$params[':value'] = "%$value%";
			} elseif ($searchBy === 'actor') {
				$sqlPart = " 
					JOIN films_actors AS fa ON fa.film_id = films.id
					JOIN actors AS a ON a.id = fa.actor_id
					WHERE a.firstname LIKE :value_1 OR a.lastname LIKE :value_2
				";
				$params[':value_1'] = "%$value%";
				$params[':value_2'] = "%$value%";
			}

			try {
				$sql = "SELECT DISTINCT films.id, title FROM films $sqlPart ORDER BY title;";
				$stmt = self::$db_connection->prepare($sql);
				$stmt->execute($params);
				
				return $stmt->fetchAll(\PDO::FETCH_ASSOC);
			} catch(\PDOException $e) {
				var_dump($e->getMessage());
				return false;
			}
		}
	}
	