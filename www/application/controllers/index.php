<?php

	class Application_Controllers_Index  extends Lib_BaseController {		//контролер главной страницы
		function index() {
			$model = new Application_Models_Index;
		
			$this->mainMenuCatalogs = $model->getMainMenuCatalogs();		// главное меню сайта берем из базы
			$this->popularProducts = $model->getPopularProducts();			// популярные продукты
			$this->saleProducts = $model->getSaleProducts();				// акция
		}
	}

?>