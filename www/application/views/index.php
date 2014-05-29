	
<?
	foreach ($mainMenuCatalogs as $i => $v) {			// собираем главное меню из базы
	
		echo "<div class='block-menu-for-dog'>";
		echo 	"<a href='/catalog/{$v['mainCatalog']['url']}' class='block-menu-for-dog-title'>" . $v['mainCatalog']['nameRus'] . "</a>";
		echo 	"<div class='block-menu-for-dog-submenu'>";
		echo 		"<ul class='ul_from_submenu'>";
		
		foreach ($v['kids'] as $k)
			echo "<li> <a href='/catalog/{$k['url']}'> <div></div> {$k['nameRus']}</a></li>";
		
		echo 		"</ul>";
		echo 	"</div>";
		echo "</div>";
	}
?>


	<div class="show_sale_product">
    <ul id="tabs">
        <li><a href="#" title="tab1">ПОПУЛЯРНОЕ</a></li>
        <li><a href="#" title="tab2">АКЦИОННОЕ</a></li>
    </ul>

    <div id="content">
        <span id="tab1">
<?			foreach ($popularProducts as $item)	{	?>
				<div class="product">

					<div class="product_image">
						<a href="/<?=$item['product_url']?>">
							<img src="/<?=$item['picture_url']?>"> 
						</a>
					</div>

					<div class="product_name_in_list">
						<a href="/<?=$item['product_url']?>">
							<h2><?=$item["name"]?></h2>
						</a> 
					</div>

                    <div class="product_for_in_list" alt="fffff">
                        <a href="/<?=$item['product_url']?>">
                            <h2><?=$item['short_descr']?></h2>
                        </a>
                    </div>

                    <table class="simple-little-table" cellspacing='2'>
                        <tr>
						
							<? if ($item['weight']) 		// если у товара есть вес
								echo "<th>Вес:</th>";
							?>
							
							<? 	if ($item['price']) { 		// если у товара есть цена (есть в наличии)
									echo "<th>Цена:</th>";
									echo "<th>Добавить</th>";
								}
								else
									echo "<th style=''>Наличие:</th>";
							?>
                        </tr><!-- Table Header -->

                        <tr>
						
							<? if ($item['weight']) 
								echo  '<td>' . $item['weight'] . '</td>';
							?>
                           
							<? 	if ($item['price']) { 		// если у товара есть цена (есть в наличии)	?>
									<td> <?=$item['price']?> р</td>
									<td style='width: 93px;'>
										<div class="product_buy" onClick="location.href='/cart?in_cart_catalog_id=<?=$item['id_catalog']?>&in_cart_product_id=<?=$item['id']?>'">
											В корзину
										</div>
									</td>
							<?	}
								else
									echo '<td>Товар отсутствует</td>';
							?>
                            
                        </tr>
                    </table>

				</div>
<?			}	?>
		</span>



        <span id="tab2">
               <span id="tab1">
    <?			foreach ($saleProducts as $item)	{	?>
					<div class="product">

					<div class="product_image">
						<a href="/<?=$item['product_url']?>">
							<img src="/<?=$item['picture_url']?>"> 
						</a>
					</div>

					<div class="product_name_in_list">
						<a href="/<?=$item['product_url']?>">
							<h2><?=$item["name"]?></h2>
						</a> 
					</div>

                    <div class="product_for_in_list" alt="fffff">
                        <a href="/<?=$item['product_url']?>">
                            <h2><?=$item['short_descr']?></h2>
                        </a>
                    </div>

                    <table class="simple-little-table" cellspacing='2'>
                        <tr>
						
							<? if ($item['weight']) 		// если у товара есть вес
								echo "<th>Вес:</th>";
							?>
							
							<? 	if ($item['price']) { 		// если у товара есть цена (есть в наличии)
									echo "<th>Цена:</th>";
									echo "<th>Добавить</th>";
								}
								else
									echo "<th style=''>Наличие:</th>";
							?>
                        </tr><!-- Table Header -->

                        <tr>
						
							<? if ($item['weight']) 
								echo  '<td>' . $item['weight'] . '</td>';
							?>
                           
							<? 	if ($item['price']) { 		// если у товара есть цена (есть в наличии)	?>
									<td> <?=$item['price']?> р</td>
									<td style='width: 93px;'>
										<div class="product_buy" onClick="location.href='/cart?in_cart_catalog_id=<?=$item['id_catalog']?>&in_cart_product_id=<?=$item['id']?>'">
											В корзину
										</div>
									</td>
							<?	}
								else
									echo '<td>Товар отсутствует</td>';
							?>
                            
                        </tr>
                    </table>

				</div>
    <?			}	?>
            </span>
        </span>

    </div>
</div>


<!-- ------- Карусель для новостей -->

    <h2 class="News_title">Новости</h2>
    <div class="carousel">
        <a href="#" class="arrow left-arrow" id="prev">&#8249; </a>
        <div class="gallery">
            <ul id="images">
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 09.04.2014</a>
                        <p>По техническим причинам наш магазин 10.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 10.04.2014</a>
                        <p>По техническим причинам наш магазин 11.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 11.04.2014</a>
                        <p>По техническим причинам наш магазин 12.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 11.04.2014</a>
                        <p>По техническим причинам наш магазин 13.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 12.04.2014</a>
                        <p>По техническим причинам наш магазин 14.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 13.04.2014</a>
                        <p>По техническим причинам наш магазин 15.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
                <li>
                    <div class="news_block">
                        <a href='#'> <div></div> 14.04.2014</a>
                        <p>По техническим причинам наш магазин 16.04.2014 доставку не производит. Заказы принимаются сегодня на завтра!</p>
                    </div>
                </li>
            </ul>
        </div>
        <a href="#" class="arrow right-arrow" id="next">&#8250; </a>
    </div>

    <script>
    /* конфигурация */
    var width = 235; // ширина изображения
    var count = 4; // количество изображений

    var ul = document.getElementById('images');
    var imgs = ul.getElementsByTagName('li');

    var position = 0; // текущий сдвиг влево

    document.getElementById('prev').onclick = function() {
    if (position >= 0) return false; // уже до упора

    // последнее передвижение влево может быть не на 3, а на 2 или 1 элемент
    position = Math.min(position + width*count, 0)
    ul.style.marginLeft = position + 'px';
    return false;
    }

    document.getElementById('next').onclick = function() {
    if (position <= -width*(imgs.length-count)) return false; // уже до упора

    // последнее передвижение вправо может быть не на 3, а на 2 или 1 элемент
    position = Math.max(position-width*count, -width*(imgs.length-count));
    ul.style.marginLeft = position + 'px';
    return false;
    };
    </script>