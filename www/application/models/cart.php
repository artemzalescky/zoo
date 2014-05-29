<?php
		//Модель вывода корзины	
class Application_Models_Cart {

    function addToCart($id_catalog, $id, $count=1){	// добавление в корзину по id каталога, id товара и его количеству 
		$ff=false;	// флажок, есть ли уже такой товар
		if ($_SESSION['cart']) {
			foreach ($_SESSION['cart'] as $i=>$product) { // проверяем, есть ли такой товар в корзине уже
				if ($product['id']==$id  &&  $product['id_catalog']==$id_catalog && $ff==false) {	// если есть
					$_SESSION['cart'][$i]['count']+=$count;		// просто изменяем количество
					$ff=true;	//нашли
				}
			}
		}
		
		if ($ff == false) {	//если нет такого товара
			$_SESSION['cart'][]=array(		// то добавляем
				"id_catalog" => $id_catalog,
				"id" => $id,
				"count" => $count
			);
		}
		
		return true;
    }
	
	
	function getProductCount($id_catalog, $id){	// получить кол-во продукта
		foreach ($_SESSION['cart'] as $i=>$product){
			if($product['id']==$id  &&  $product['id_catalog']==$id_catalog)	// если есть
				return $_SESSION['cart'][$i]['count'];
		}
		
		return -1;
	}
	
	
	function getCartProductsInfo() {		// получить цены и названия товаров из корзины
		if ($_SESSION['cart']) {
			foreach($_SESSION['cart'] as $i => $product) {
				$catalog_name = getCatalogNameById1($product['id_catalog']);
				
				$sql = "SELECT * FROM $catalog_name WHERE id='{$product['id']}'";
				$result = mysql_query($sql);
				
				if ($result == null) {		// такого товара уже не существует
					$this->deleteFromCart($product['id_catalog'], $product['id']);
				}

				if ($result && $row = mysql_fetch_object($result)) {				
					$res['prices'][$i] = $row->price;		// res - это 3 массива: из цен,  имен , доп инфы
					$res['names'][$i] = $row->name;
					$res['extra_info'][$i] = null;	// доп. информации не надо
				}
			}
			
			Lib_SmalCart::getInstance()->setCartData();		// сохр в куки (проверяется наличие данного товара)
		}
		return $res;
	}
	  
	  
    function deleteFromCart($id_calalog,$id, $count=1) {		// удалить товар из корзины
		foreach($_SESSION['cart'] as $i => $product){
			if($id_calalog == $product['id_catalog'] && $id == $product['id']){		// нашли данный товар в корзине
				unset($_SESSION['cart'][$i]);
				return true;
			}
		}
		return false;
	}
    
    function clearCart() {	// очистить корзину
		unset($_SESSION['cart']);				// из сессии
		setcookie("cart", "", time() - 3600);	// из cookies (время смерти = текущее - 1час, т.е. куки сразу удаляется)
	}
}

function getCatalogNameById1($id) {
	$sql = "SELECT * FROM catalog WHERE id='$id'";
	$result = mysql_query($sql);

	if($result && $row = mysql_fetch_object($result)){
		return $row->name;
	}
	return null;
}

function getCatalogUrlById($id) {
	$sql = "SELECT * FROM catalog WHERE id='$id'";
	$result = mysql_query($sql);

	if($result && $row = mysql_fetch_object($result)){
		return $row->url;
	}
	return null;
}

?>