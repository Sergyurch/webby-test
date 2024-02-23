<?php
    namespace Project\Controllers;
    use \Core\Controller;
    use \Core\View;
    use \Project\Models\Film;

    class FilmController extends Controller {
        public function showAll() {
            $this->title = 'Список фільмів';
            $data = (new Film)->getAll();
            $page = $this->getPage('film/showAll', ['films' => $data]);

            echo (new View)->render($page);
		}

        public function showById($params) {
            extract($params);
            $this->title = 'Опис фільму';
            $data = (new Film)->getById($id);
            $page = $this->getPage('film/showById', ['film' => $data[0]]);

            echo (new View)->render($page);
		}

        public function add() {
            $this->title = 'Додати новий фільм';
            $data = [];

            if (!empty($_POST)) {
                $data = $_POST;
                $errors = $this->checkDataForErrors();

                if ($errors) {
                    $data['errors'] = $errors;
                } else {
                    $result = (new Film)->add($data);

                    if ($result) {
                        $data = [];
                        $data['success'] = 'Фільм успішно додано.';
                    } else {
                        $data['errors'][] = 'Виникла помилка. Спробуйте ще раз або зверніться до адміністратора сайту.';
                    }
                }
            }
                        
			$page = $this->getPage('film/add', $data);

            echo (new View)->render($page);
		}

        public function deleteMany() {
            if (empty($_POST['films'])) {
                echo json_encode(false);
            } else {
                $result = (new Film)->deleteMany($_POST['films']);
                echo json_encode($result);
            }
        }

        public function search() {
            if (empty($_POST['searchBy']) && empty($_POST['searchValue'])) {
                echo json_encode(false);
            } else {
                $result = (new Film)->search($_POST['searchBy'], $_POST['searchValue']);
                echo json_encode($result);
            }
        }

        public function import() {
            if (empty($_FILES)) {
                echo json_encode(false);
            } else {
                $filepath = $_FILES['file']['tmp_name'];
                $films = $this->getFilmsListFromFile($filepath);
                $filmsAddedCount = 0;
                $filmModel = new Film;

                foreach($films as $film) {
                    $film_exists = $filmModel->checkExists($film['title']);

                    if (!$film_exists) {
                        $result = $filmModel->add($film);

                        if ($result) $filmsAddedCount++;
                    }
                }

                echo json_encode(['total' => count($films), 'uploaded' => $filmsAddedCount]);
            }

        }

        private function getFilmsListFromFile($filepath) {
            $content = file_get_contents($filepath);
            $filmsRawList = explode(PHP_EOL.PHP_EOL, $content);
            $films = [];

            foreach($filmsRawList as $filmDataString) {
                $filmDataParts = explode(PHP_EOL, trim($filmDataString));

                if (count($filmDataParts) < 4) continue;

                $filmDataValues = array_map(function($filmDataPart) {
                    $splitPosition = stripos($filmDataPart, ':');

                    if (!$splitPosition) return null;

                    $value = substr($filmDataPart, $splitPosition + 1);

                    if (!$value) return null;

                    return trim($value);
                }, $filmDataParts);

                if (in_array(null, $filmDataValues, true)) continue;
                
                $filmDataValues[2] = array_map(function($elem) {
                    return trim($elem);
                }, explode(',', $filmDataValues[2]));

                $films[] = array_combine(['title', 'year', 'formats', 'actors'], $filmDataValues);
            }

            return $films;
        }

        private function checkDataForErrors() {
            if (empty($_POST['title'])) {
                $errors[] = 'Не заповнена назва фільму';
            }

            if (empty($_POST['year'])) {
                $errors[] = 'Не заповнений рік фільму';
            }

            if (empty($_POST['actors'])) {
                $errors[] = 'Не заповнене поле з акторами';
            }

            if (empty($_POST['formats'])) {
                $errors[] = 'Не вибрані доступні формати фільму';
            }

            if (!empty($_POST['title'])) {
                $filmExists = (new Film)->checkExists($_POST['title']);
                
                if ($filmExists) {
                    $errors[] = 'Фільм з такою назвою вже існує';
                }
            }

            return $errors ?? null;
        }
    }
