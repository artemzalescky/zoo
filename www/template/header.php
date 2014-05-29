<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8">
		
		<title>Zoo.by</title>

		<link href="/css/main.css" rel="stylesheet" type="text/css">
		<link href="/css/navigation.css" rel="stylesheet" type="text/css">
		<link href="/css/product_list.css" rel="stylesheet" type="text/css">
		<link href="/css/cart.css" rel="stylesheet" type="text/css">
		<link href="/css/admin.css" rel="stylesheet" type="text/css">
		<link href="/css/show_sale.css" rel="stylesheet" type="text/css">
        <link href="/css/delivery.css" rel="stylesheet" type="text/css">   <!--доставка и оплата-->
        <link href="/css/contacts.css" rel="stylesheet" type="text/css">   <!--Контакты-->
		
		
		<!-- Подключение javascript -->

        <!-- Для товаро акцинных и самых популярных - на гланой странице -->
        <script src="http://code.jquery.com/jquery-1.6.3.min.js"></script>
        <script src="/js/show_sale_product.js"></script>
		
		<!-- Новости -->
		<script src="/js/news.js"></script>

		<!-- Администраторская панель -->
		<script src='/js/classlib.js'> </script>
		<script src='/js/admin.js'> </script>

		
		
		<?	if ($_controller_name=="product") { // чтоб подключать только для продукта ?>
			
				<link href="/css/product.css" rel="stylesheet" type="text/css">		
					
				<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
				<script type="text/javascript" src="/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
				<script type="text/javascript" src="/fancybox/jquery.easing-1.4.pack.js"></script>
				<script type="text/javascript" src="/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
				<link rel="stylesheet" href="/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
				
				<script type="text/javascript">
					$(document).ready(function() {			
						$("a#show_picture").fancybox();
					});
				</script>
			<? } ?>
	</head>

<body>

	<div id="container">
		<div id='header'>

		    <div id="site_logo">
                <a href="/">
                    <img id="img_site_logo" src="/images/main/logo.jpg" alt="главная">
                </a>
            </div>

            <div id="site_name">
                <a href="/">
                    <img id="img_site_name" src="/images/main/title.png">
                </a>
            </div>

            <div id='div_cart' onmouseover="document.getElementById('cart_title').src='/images/main/cart_title_hover.png';"
                 onmouseout="document.getElementById('cart_title').src='/images/main/cart_title.png';">
                <a href='/cart'>
                    <img src='/images/main/cart.jpg'>
                    <div style='float:left'>
                        <img id='cart_title' src='/images/main/cart_title.png' style='margin-top:0px'>
						<div style='clear:both'> </div>
						<div>
							<span> <b>Товаров в корзине: <i style='color:#FF8200'><?=$smal_cart['cart_count']?></i></b> </span> <br>
							<span> <b>На сумму: <i style='color:#FF8200'><?=$smal_cart['cart_price']?></i> руб. </b> </span>
						</div>
                    </div>
                </a>
            </div>

            <div id="main_menu">
                <a class="menu" href='/catalog'>
                    <img  src="/images/menu/katalog.png">

                    <div class="name-child">
						<div style="padding-top: 5px">Каталог товаров</div>
                    </div>
                </a>

                <a class="menu" href='/delivery'>
                    <img  src="/images/menu/time.jpg">

                    <div class="name-child">
						<div style="padding-top: 5px">Доставка и оплата</div>
                    </div>
                </a>

                <a class="menu" href='/contacts'>
                    <img  src="/images/menu/contact.jpg">

                    <div class="name-child" >
                       <div style="padding-top: 5px">Контакты</div>
                    </div>
                </a>
            </div>

            <div id="form-poisk">
                <img  src="/images/main/kate2.jpg">

                <form id='header_search' method='GET' action='/search'>
                    <input type='text' name='words' autocomplete='off' placeholder="Что будем искать..">
                    <button id='search_button'>Поиск</button>
                </form>
            </div>

		</div>

