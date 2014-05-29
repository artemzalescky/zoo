<?php
	
	//контролер отправки почты
	
	class Application_Controllers_Mail  extends Lib_BaseController {
		function index() {
			$model = new Application_Models_Mail;
			$this->success = $model->sendOrder();	// отправляем заказ на почту магазина
		}
	}
	
?>