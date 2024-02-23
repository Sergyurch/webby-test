<?php
	namespace Project\Models;
	use \Core\Model;

    class Actor extends Model {
        public function getActorsIdsByFullnames($actorsList) {
            $idList = [];
            
            if (!is_array($actorsList)) {
                $actorsList = explode(',', $actorsList);
            }
            
            foreach($actorsList as $actor) {
                $fullName = preg_replace('/\s+/', ' ', trim($actor));
                $fullNameParts = explode(' ', $fullName);
                if (count($fullNameParts) !== 2) continue;

                $firstname = $fullNameParts[0];
                $lastname = $fullNameParts[1];
                $actorId = $this->getActorId($firstname, $lastname);

                if ($actorId) {
                    $idList[] = $actorId['id'];
                } else {
                    $idList[] = $this->addActor($firstname, $lastname);
                };
            }

            return $idList;
        }

        public function addActor($firstname, $lastname) {
            $sql = "INSERT INTO actors (firstname, lastname) VALUES (:firstname, :lastname);";
            $stmt = self::$db_connection->prepare($sql);
            $stmt->execute([':firstname' => $firstname, ':lastname' => $lastname]);

            return self::$db_connection->lastInsertId();
        }

        private function getActorId($firstname, $lastname) {
            $sql = "SELECT id FROM actors WHERE firstname = :firstname AND lastname = :lastname;";
            $stmt = self::$db_connection->prepare($sql);
            $stmt->execute([':firstname' => $firstname, ':lastname' => $lastname]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }
