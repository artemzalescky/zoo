<?php

	// модель отправки почты
	
	class Application_Models_Mail {
	
		function sendOrder() {		// отправить заказ на нашу почту
			if (!$_POST['cart2_name'] || !$_POST['cart2_address'] || !$_POST['cart2_phone'] || !$_POST['cart2_email']) {	// есть некорректный параметр
				header('Location: /cart2');
			}
			
			$customer_name = $_POST['cart2_name'];
			$customer_address = $_POST['cart2_address'];
			$customer_phone = $_POST['cart2_phone'];
			$customer_email = $_POST['cart2_email'];
			
			// информация о товарах из корзины
			$cart_model = new Application_Models_Cart; 
			$info = $cart_model->getCartProductsInfo();
			$prices = $info['prices'];
			$names = $info['names'];
			$extra_info = $info['extra_info'];		// доп. информация в названии (типа размеров для труб/соединений)
			
			$smal_cart = Lib_SmalCart::getInstance()->getCartData();	// т.к. function.php подключается позже, достаем инфу маленькую корзину вручную
			
			
			$to = "minzer_alex@mail.ru";					// ## получатель
			$subject = "Зоомагазин (заказ товаров)";		// заголовок письма
			
			$message = "    Данные покупателя\n\n	Имя: $customer_name\n	Адрес: $customer_address\n	Телефон: $customer_phone\n	e-mail: $customer_email\n";
			$message .= "--------------------------------------------------------------------------------------------------------\n\n";
			$message .= "Заказанные товары:\n";
			$message .= "---------------------------------------------------------------\n";
			
			
			foreach ($_SESSION['cart'] as $i => $product) {		// записываем сам заказ (данные из корзины)
				$message .= $names[$i] . $extra_info[$i] . "\n";
				$message .= "цена: " . $prices[$i] . "\n";
				$message .= "количество: " . $product['count'] . "\n";
				$message .= "в сумме: " . $product['count']*$prices[$i] . "\n";
				$message .= "( товар: " . '/product/' . getCatalogUrlById($product['id_catalog']) . '/' . $product['id'] . " )\n";		// ## сделать ссылку
				$message .= "---------------------------------------------------------------\n";
			}
		
			$message .= "К оплате: " . $smal_cart['cart_price'] . "\n";
			$message .= "--------------------------------------------------------------------------------------------------------\n\n";
			
			//echo nl2br($message);
			
			if (mail($to,$subject,$message))		// успешная доставка
				return true;
			return false;							// письмо не доставлено
		}
		
	}
	
?>