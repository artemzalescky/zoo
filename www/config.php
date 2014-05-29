<?php

	ini_set('error_reporting', E_ALL);		// вывод редупреждений и ошибок
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	Error_Reporting(E_ALL & ~E_NOTICE);		//не выводить предупреждения

	date_default_timezone_set("Europe/Lisbon");		// ## чтоб не было предупреждений о не заданной временной зоне


	function __autoload ($class_name) { 			// автоматическая загрузка кслассов (включение в файл кода класса) ,переопределяем стандартную функцию
		$path=str_replace("_", "/", $class_name);	// разбивает имя класса получая из него путь
		include_once($path .".php");				// подключает php файл с классом по полученному пути	
	}

	//	константы для подключения к базе данных
	define('HOST', 'zoo.loc'); 		// ## сервер
	define('USER', 'root'); 		// пользователь
	define('PASSWORD', 'root');		// пароль
	define('NAME_BD', 'zoochange');		// имя базы данных
	
	// соединение с сервером
	$connect = mysql_connect(HOST, USER, PASSWORD) or die("Невозможно установить соединение сервером: ".mysql_error());
	
	// соединение с БД
	mysql_select_db(NAME_BD, $connect) or die ("Ошбка обращения к базе ".mysql_error());	
	mysql_query('SET names "utf8"');   // устанавливаем кодировку данных в базе
?>