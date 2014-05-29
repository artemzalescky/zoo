
<?	
	// выводим путь к каталогу товара
?>
	<div id="catalog_tree">
<?	
		foreach ($product['catalog_tree'] as $i => $v) {
			echo '<a href="/' . $v['url'] . '"><div class="item_of_tree">' . $v['name_rus'] . '</div></a>';
		
			if ($i<count($product['catalog_tree'])-1) {
				echo '<div><img src="/images/next.png"> </div>';
			}
		}
?>
	</div>
	<div style="clear:both"></div>


	
<div style='padding: 5px 15px; margin: 5px 0; border-radius: 15px; background-color: #8CBF00;'> </div>

<div id="product_container">

	<div id="product_container_title">
		<h3><?=$product['name']?>&nbsp;<?=$product['for']?></h3>
	</div>

	<div id="product_container_image">
		<a id="show_picture" href="/<?=$product['picture_url']?>"> <img style="float:right;" src="/<?=$product['picture_url']?>"> </a>
	</div>

	<div id="product_container_description">
        <div class="block_price">
			<div id="block_price_first">
				Цена:
				<br>
				<span><strong><?=$product['price']?></strong> р</span>
			</div>
			<div id="block_price_second">
				Количество:
				<input type="text" id="count_product" name="count_product" value="1">
			</div>
			<div id="block_price_third">
				<div class="product_buy" onClick="location.href='/cart?in_cart_catalog_id=<?=$product['id_catalog']?>&in_cart_product_id=<?=$product['id']?>&in_cart_count='+ document.getElementById('count_product').value" style='float:left; font-weight:bold'>
					В корзину
				</div>
			</div>
        </div>

<?
	foreach ($product as $i => $v) {
		if ($v) {	// если параметр задан, т.е. не null
			if (!empty($product['eng_rus']["$i"]) && ($product['eng_rus']["$i"] != '#'))	// выводим русское название атрибута
				echo '<div><b>' . $product['eng_rus']["$i"] . '</b> : ' . str_replace("\n","</div><div>",$v) . '</div>';
				
			if (empty($product['eng_rus']["$i"]))	// не выводим русское название
				echo '<div>' . str_replace("\n","</div><div>",$v) . '</div>';
		}
	}
?>
		<div> <b>Цена</b>: <span style='color:#E84900'> <?=$product['price']?> </span> р. </div>	
	

	</div>

	<div id="product_container_footer"></div>
	
</div>	

<div style='padding: 5px 15px; margin: 10px 0; border-radius: 15px; background-color: #8CBF00;'> </div>