<?
	// модель аторизации

	class Application_Models_Auth  extends Lib_BaseController {
	
		function checkAuth() {		// функция проверки авторизации (переменная в сессии)
			return isset($_SESSION['user_name']) ? true : false;
		}
		
		function checkLoginAndPassword($login, $password) {		// проверяем на наличие логина и пароля (база данных)
			$hash_pass = md5($password);	// хэшируем пароль (в базе они хранятся в таком виде)
			$sql = "SELECT * FROM login WHERE login='$login' AND password='$hash_pass'";
			$result = mysql_query($sql) or die(mysql_error());
			
			if ($row = mysql_fetch_object($result)) {		// в базе есть данная пара логин/пароль
				return $row->login;
			}
			
			return null;		// неверный логин/пароль
		}
		
		function login($user_name) {	// войти
			$_SESSION['user_name'] = $user_name;
		}
		
		function logout() {				// выйти
			unset($_SESSION['user_name']);
		}
	}
	
?>