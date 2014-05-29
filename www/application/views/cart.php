
<? if ($smal_cart['cart_count'] != 0) {		// есть товары
?>
	<div id="cart">

		<h2 id="cart_title">Корзина товаров</h2>

		
		<form action="cart" method="GET">	<!-- изменения количества будут обрабатываться в контроллере cart-->
			<table id="cart_table">
				<tr>  
					<th>Название товара</th>
					<th>Цена</th>
					<th>Количество</th>
					<th>Сумма</th>
					<th>Удалить из корзины</th>
				</tr>	
			<?
			if ($_SESSION['cart']) {
				foreach($_SESSION['cart'] as $i => $product){
			?>
				<tr class="simple_row">
					<td style="text-align:left;">
						<a href="/product/<?=getCatalogUrlById($product['id_catalog'])?>/<?=$product['id']?>"> 
							<?=$names[$i]?> <?=$extra_info[$i]?>
						</a>
					</td>
					<td>
						<?=$prices[$i]?>
					</td>
					<td style="width:70px;">
						<!--  имя формы количества "productcount_c_p"  с-ид каталога , р-ид товара -->
						<!-- onclick="this.select()"  -  текст ввода выделяется при клике по форме -->
						<!-- onblur= - щелкнули вне формы -->
						<input  class="count" 
								type="text" 
								onclick="this.select()"
								onblur="location.href='/cart?' + this.name + '=' + this.value; return false;" 
								name="productcount_<?=$product['id_catalog']?>_<?=$product['id']?>"
								value="<?=$product['count']?>">
					</td>
					<td>
						<?=$product['count']*$prices[$i]?>
					</td>
					<td style="width:150px;">
						<a href="/cart?del_from_cart_id_catalog=<?=$product['id_catalog']?>&del_from_cart_id_product=<?=$product['id']?>" >
							Удалить
						</a>
					</td>
				</tr>
			<?
				}	// end of foreach
			}
			?>

				<tr>
					<th colspan="2"></th>
					<th>К оплате:</th>
					<td><?=$smal_cart['cart_price']?></td>
					<th><a href="/cart?del_all_from_cart=1" style='display: block; color: #fff'>Очистить корзину</a></th>
				</tr>

			</table>
		</form>

		<div style="clear:both"></div>

			<div class="cart_button" onClick="location.href='/catalog'">
				Вернуться к каталогу
			</div>

			<div class="cart_selected" onClick="location.href='/cart2'">
				Оформить заказ
			</div>

		<div style="clear:both; margin-bottom:40px;"></div>
	</div>

<? 	}
	else {		// нет товаров
?>
		<div id="cart">
			<h2 id="cart_title" style='margin-left: 100px; color: #ff8200'>Корзина товаров пуста</h2>
			
			<div class="cart_button" onClick="location.href='/catalog'" style='margin-left: 100px'>
				Вернуться к каталогу
			</div>
		</div>

<? 	}	?>