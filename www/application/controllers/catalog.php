<?php

	// контроллер для запросов по категориям каталога
			
	class Application_Controllers_Catalog  extends Lib_BaseController {
		function index() {  		
	
			$model = new Application_Models_Catalog;
			
			$info = $model->getCatalogInfo();		// получаем информацию о каталоге
			$this->info = $info;					// пишем в member  (переопределенные ф-ии __set() __get()  для member в BaseController)

			if (!empty($info['name'])) {	// не самый верхний каталог 
				$this->products = $model->getProducts($info['name'], $info['kids']);	// список всех продуктов каталога и всех его детей
			}
			else {				// выводим меню главного каталога
				$index_model = new Application_Models_Index;
				$this->mainMenuCatalogs = $index_model->getMainMenuCatalogs();
			}
		}
	}

?>