<?
	if ($login_logs == 'include_form') {						// просто форма авторизации (запроса еще не было)
		echo "<div style='padding-top: 20px; color: #333; text-align: center;'><b>Для входа в администраторскую панель необходимо авторизоваться</b></div>";
		include ("/template/login_form.php");
	}

	if ($login_logs == 'right_login_password') {				// успешная авторизация
		echo "<div style='padding-bottom: 20px; color: #333; text-align: center;'><b>Авторизация прошла успешно</b></div>";
		echo "<div style='padding-bottom: 20px; color: #333; text-align: center;'><a style='color:333' href='/admin'>Перейти к администраторской панели</a></div>";
	}
	
	if ($login_logs == 'wrong_login_password') {				// неверный логин/пароль
		echo "<div style='padding-top: 20px; color: #e00; text-align: center;'><b>Неверный логин или пароль. Повторите ввод данных</b></div>";
		include ("/template/login_form.php");
	}
	
	if ($login_logs == 'logout') {								// выход
		echo "<div style='padding-top: 20px; color: #333; text-align: center;'><b>Вы вышли из администраторской панели</b></div>";
	}
?>