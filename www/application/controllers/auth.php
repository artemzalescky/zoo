<?
	// контроллер авторизации

	class Application_Controllers_Auth  extends Lib_BaseController {
		function index() {  
			$model = new Application_Models_Auth;
			
			$login_logs = 'include_form';		// логи авторизации
			
			if (isset($_GET['logout'])) {		// запрос на выход
				$login_logs = 'logout';
				$model->logout();				// выходим
			}
			
			if ($model->checkAuth()) {			// если уже были авторизованы
				header('Location: /admin');		// перекидываем в админку
			}
			
			if (isset($_POST['login_input']) && isset($_POST['password_input'])) {	// подан запрос на авторизацию

				$login = $model->checkLoginAndPassword($_POST['login_input'], $_POST['password_input']);	// имя если все верно; null если нет
				if ($login) {
					$login_logs = 'right_login_password';
					$model->login($_POST['password_input']);	// имя пользователя в сессию
					header('Location: /admin');					// если авторизовались, перекидываем в админку
				}
				else {
					$login_logs = 'wrong_login_password';
				}
			}

			$this->login_logs = $login_logs;
		}
	}
	
?>