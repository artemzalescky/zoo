<?php

//Клас моделирует маленькую корзину

class Lib_SmalCart {
  
    protected static $instance; //(экземпляр объекта) Защищаем от создания через new Singleton
	
	private function __construct() {}
	
	public static function getInstance(){ 	//Возвращает единственный экземпляр класса
		if(!is_object(self::$instance))
			self::$instance = new self;
		return self::$instance;
    }
	
	
	public function  setCartData() { // записывает в cokie текущее состояние корзины в сериализованном виде		 
		  $cart_content = serialize($_SESSION['cart']); 		// сериализует  данные корзины из сессии в строку
		  SetCookie("cart", $cart_content,time()+3600*24*365); 	//записывает сериализованную строку в куки, хранит 1 год
	}
	 
	protected function  getCokieCart(){ // получить данные из куков назад в сессию
	   if(isset($_COOKIE['cart'])){ // если куки уже были установлены	
			$_SESSION['cart']=unserialize($_COOKIE['cart']); //десериализует строку в массив
			return  true;
	   }
	   
	   return  false;		 
	}
	
	public function getCartData() { //Получить общую сумму товаров корзины + их количество

	 	$res['cart_count']=0; //количество вещей в корзине
		$res['cart_price']=0; //общая стоимость
		$total_count=0;
		$total_price=0;
		
	   if($this->getCokieCart() && $_SESSION['cart']) { //если удалось получить данные из куков и они успешно десериализованы в $_SESSION['cart']
	   
			foreach ($_SESSION['cart'] as $i=>$product) { // пробегаем по содержимому, вычилсяя сумму и количество
			
				$catalog_name = getCatalogNameById($product['id_catalog']);
				
				$sql = "SELECT * FROM $catalog_name WHERE id='{$product['id']}'";
				$result = mysql_query($sql);

				if ($result && $row = mysql_fetch_object($result)) {		// если куки актуальны (такие товары еще есть) 
					$total_price += ($row->price)*$product['count'];
					$total_count += $product['count'];
				}
			}

			$res['cart_count'] = $total_count;
			$res['cart_price'] = $total_price;
		}
		
		return  $res;
	}
}
 
function getCatalogNameById($id) {
	$sql = "SELECT * FROM catalog WHERE id='$id'";
	$result = mysql_query($sql);

	if($result && $row = mysql_fetch_object($result)){
		return $row->name;
	}
	return null;
}
 
?>