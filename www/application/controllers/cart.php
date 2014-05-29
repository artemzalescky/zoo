<?php
			// контроллер для большой корзины
class Application_Controllers_Cart  extends Lib_BaseController
{
    function index() 
	{
		// запрос на добавление товара в корзину(по ид каталога и ид товара)
		if($_REQUEST['in_cart_product_id']  &&  $_REQUEST['in_cart_catalog_id']){   // запрос на добавление в корзину товара (по ид каталога и ид товара)
		   $cart=new Application_Models_Cart;													// создаем модель корзины
		   if(!isset($_REQUEST['in_cart_count']))		// если не задано количество
				$_REQUEST['in_cart_count']=1;
		   $cart->addToCart($_REQUEST['in_cart_catalog_id'],$_REQUEST['in_cart_product_id'],$_REQUEST['in_cart_count']);   // добавляем в объект модели товар
		   Lib_SmalCart::getInstance()->setCartData();											// сохраняем корзину в cookies			   
		   header("Location: /".$_SESSION['previous_url']);										// возвращаемся на исходную страницу
		   exit;
		}
			
		// запрос на удаление товара из корзины(по ид каталога и ид товара)
		if($_REQUEST['del_from_cart_id_catalog']  &&  $_REQUEST['del_from_cart_id_product']){  
			$cart=new Application_Models_Cart;													// создаем модель корзины
			$cart->deleteFromCart($_REQUEST['del_from_cart_id_catalog'],$_REQUEST['del_from_cart_id_product']);   // удаляем (из сессии)
			Lib_SmalCart::getInstance()->setCartData();											// сохраняем корзину в cookies
			header("Location: /".$_SESSION['previous_url']);		// возвращаемся на исходную страницу
			exit;
		}
		
		// запрос очистку корзины
		if($_REQUEST['del_all_from_cart']  &&  $_REQUEST['del_all_from_cart']==1){
			$cart=new Application_Models_Cart;						// создаем модель корзины
			$cart->clearCart();   									// чистим
			//Lib_SmalCart::getInstance()->setCartData();			// сохраняем корзину в cookies	
			header("Location: /".$_SESSION['previous_url']);		// возвращаемся на исходную страницу
			exit;
		}
		
		// если надо пересчитать количества товаров в корзине
		if($this->countIsChanged()){		
			$this->changeCount();
		}
		
		$model = new Application_Models_Cart; 
		$info = $model->getCartProductsInfo();	// информация о товарах из корзины (цены  и названия товаров)
		$this->prices = $info['prices'];
		$this->names = $info['names'];
		$this->extra_info = $info['extra_info'];		// доп. информация в названии (типа размеров для труб/соединений)
    }
	
	
	// доп ф-ии
	function countIsChanged(){	// надо ли пересчитать количества товаров в корзине
		foreach($_GET as $i=>$v){
			$arr=explode('_',$i);
			if($arr[0]=='productcount')		// есть параметр вида productcount_3_7
				return true;
		}
		return false;
	}
	
	function changeCount(){	//  пересчитываем количества товаров в корзине
		foreach($_GET as $i=>$v){
			$arr=explode('_',$i);
			if($arr[0]=='productcount'){		// есть параметр вида productcount_3_7
				$cart=new Application_Models_Cart;					// создаем модель корзины
				$delta=$v-$cart->getProductCount($arr[1],$arr[2]);	// на сколько меняем кол-во товара
				$cart->addToCart($arr[1], $arr[2], $delta);
			}
		}
		
		Lib_SmalCart::getInstance()->setCartData();				// сохраняем корзину в cookies
		header("Location: /".$_SESSION['previous_url']);		// возвращаемся на исходную страницу (т.е. в корзину)
		exit;
	}
}
?>