<?php
    namespace Project\Controllers;
    use \Core\Controller;
    use \Core\View;
    use \Project\Models\User;
    
    class PageController extends Controller {
        public function login() {
            $this->title = 'Сторінка входу';
            $data = [];
                        
            if (!empty($_POST)) {
                if (!empty($_POST['email'])) {
                    $data['email'] = $_POST['email'];
                }

                if (empty($_POST['email']) || empty($_POST['password'])) {
                    $data['errors'][] = 'Усі поля обов\'язкові до заповнення';
                } else {
                    $userModel = new User;
                    $user = $userModel->getByEmail($_POST['email']);
                                        
                    if (empty($user) || !password_verify($_POST['password'], $user[0]['password'])) {
                        $data['errors'][] = 'Неправильний логін або пароль';
                    } else{
                        $_SESSION['auth'] = true;
                        $_SESSION['user_data'] = $user[0];
                        header('Location: /');
                        return;
                    }
                }
            }
            
            if (!empty($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
                $data['registration_success'] = true;
                unset($_SESSION['registration_success']);
            }

			$page = $this->getPage('page/login', $data);

            echo (new View)->render($page);
		}

        public function register() {
            $this->title = 'Сторінка реєстрації';
            $data = [];

            if (!empty($_POST)) {
                if (empty($_POST['name'])) {
                    $data['errors'][] = 'Ім\'я обов\'язкове до заповнення';
                } else {
                    $data['name'] = $_POST['name'];
                }

                if (empty($_POST['email'])) {
                    $data['errors'][] = 'Відсутній email';
                }

                if (empty($_POST['password'])) {
                    $data['errors'][] = 'Не заповнене поле з паролем';
                }

                if (empty($_POST['confirm'])) {
                    $data['errors'][] = 'Поле з повторним паролем не заповнене';
                }

                if ($_POST['password'] !== $_POST['confirm']) {
                    $data['errors'][] = 'Паролі не співпадають';
                }

                if (!empty($_POST['email'])) {
                    $data['email'] = $_POST['email'];
                    $userModel = new User;

                    $user = $userModel->getByEmail($_POST['email']);

                    if (!empty($user)) {
                        $data['errors'][] = 'Користувач з таким email вже існує';
                    }
                }

                if (empty($data['errors'])) {
                    $newUserData['name'] = $_POST['name'];
                    $newUserData['email'] = $_POST['email'];
                    $newUserData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $result = $userModel->addNew($newUserData);

                    if ($result) {
                        $_SESSION['registration_success'] = true;
                        header('Location: /login');
                        return;
                    };
                }
            }
         
            $page = $this->getPage('page/register', $data);
            
            echo (new View)->render($page);
		}

        public function logout() {
            session_destroy();
            header('Location: /login');
        }
    }
