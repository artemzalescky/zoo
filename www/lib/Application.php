<?php

//	класс маршрутизатор, подбирает нужный контролер для обработки данных
//  запускается при каждом переходе по ссылке

class Lib_Application {

	private function getRoute() {		 // получить физический путь из введенного    (.htaccess кладет в $_GET['route'] введенный маршрут)
		if (empty($_GET['route'])) {
			$route = 'index';
		}
		else {
			$route = $_GET['route'];
			$route_arr = explode('/',$route);
			
			if ($route_arr[0] == "catalog") {		// если это подраздел каталога, то обработчиком будет сам catalog, а $_GET['route'] - параметр
				$route = "catalog";	
			}
			
			if ($route_arr[0] == "product") {		// обработчик продукта
				$route = "product";				
			}
			
			if ($route_arr[0] == "admin") {		// обработчик админки
				$route = "admin";				
			}
	   }

		return $route;
	}

	private function getController() {		// получить расположение файла требуемого контролера
	   $route = $this->getRoute();			// имя контроллера
	   $controller = 'application/controllers/' . $route . '.php';
	   return $controller;
	}

	private function getControllerClass() {	// возвр имя класса требуемого контроллера
	   $location_controller = $this->getController();
	   $cl = explode('.', $location_controller); 	// 2 эл-та: "./.../contr_name" ;  "php" 
	   $cl = $cl[0];							 	//отбрасываем расширение, получаем только путь до контролера с его именем
	   $name_contr = str_replace("/", "_", $cl);	//заменяем в пути слеши на подчеркивания, таким образом получая название класса
	   return $name_contr;
	}

	public function getView() {			//получить расположение файла представления для контролера
	   $route = $this->getRoute();
	   $view = 'application/views/' . $route . '.php';
	   return $view;
	}

	public function Run() {		// запуск процесса обработки данных.  Возвращает переменные контроллера (из модели)
	   session_start(); 	//открываем сессию (для сохранения переменных и параметров пред. использований)
	   
	   $name_contr = $this->getControllerClass();			// получаем имя класса контроллера	
	   $contr = new $name_contr;							// создаем экземпляр класса контролера
	   $contr->index();										// запускаем контролер на выполнение (index() должна быть у любого контролера)
	   $member = $contr->member;							// получаем переменные контролера
	   $member['_controller_name'] = $this->getRoute();		// доб. дополнительную переменную в member

	   return $member;
	}
}
 
?>