<?php  
// ===========================================
//		 функционал страницы
// ===========================================

$smal_cart = getSmalCart();		// в smal_cart   кладем   общую сумму всех товаров ['cart_price']    и  их количество ['cart_count']

function getSmalCart(){
	return Lib_SmalCart::getInstance()->getCartData();
}

//=====================================================================

function saveCurrentUrl(){	// сохранить текущий url-запрос с параметрами в сессии (чтоб после добавления в корзину вернуться на ту же страницу)
	$_SESSION['previous_url']=$_REQUEST['route'];

	if(count($_GET)>1){	// не только route но и параметры
		$_SESSION['previous_url'] = $_SESSION['previous_url'] .  '?';	// параметры
		foreach($_GET as $i=>$v){
			if($i!='route')
				$_SESSION['previous_url']=$_SESSION['previous_url']  .  "$i=$v&";
		}
	}
}

?>