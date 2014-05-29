<?php

	// контроллер для запросов по конкретному продукту
	
	class Application_Controllers_Product extends Lib_BaseController {
		function index() {
			$model=new Application_Models_Product;  
			$this->product = $model->getProduct();
		}
	}
	
?>