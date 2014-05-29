<?php

if (empty($info['name'])) {	// самый верхний каталог 	
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
}
else {
	
	// выводим предков текущего каталога
	?>
		<div id="catalog_tree">
	<?	
		foreach ($info['parents'] as $i => $v) {
	?>
			<a href="/catalog/<?=$v['url']?>">
				<div class="item_of_tree"> <?=$v['name_rus']?>  </div> 
			</a>
			
	<?	
			if ($i < count($info['parents'])-1) {
	?>
				<div> <img src="/images/next.png"> </div>
	<?		
			}
		}
	?>
		</div>
	<div style="clear:both"></div>



	
	<? 
	if ($info['near_kids']!=0) {
		echo '<div class="subcatalogs">';
		foreach ($info['near_kids'] as $sub) { ?>		
			<div class="sign">
				<a href="/catalog/<?=$sub['url']?>"> <div> <img src="<?=$info['catalog_image_url']?>/<?=$sub['url']?>.jpg"> </div> </a>
				<a style='display:block; text-align:center;' href="/catalog/<?=$sub['url']?>"> <?=$sub['name_rus']?> </a>
			</div>	
			<?
		}
		echo '</div>';
		echo '<div style="clear:both"></div>';
	}
	?>



	<div class='page_size_div'>

            <span style="margin-top:5px; float:left"> Выводить по: </span>
            <form method="get">
                <select name="page_size" onChange="this.form.submit();" style="padding:2px; margin-left:10px; cursor: pointer; color: #222;">
                    <option value = "20" selected="selected"> 20 </option>
                    <option value = "40" <?if($_GET['page_size']==40) echo "selected=\"selected\"";?>> 40 </option>
                    <option value = "60" <?if($_GET['page_size']==60) echo "selected=\"selected\"";?>> 60 </option>
                </select>
            </form>


    </div>



<?php
	
	if(!isset($_GET['page_size'])){		// максимальное количество товаров на странице
		$max_post = 20;
	}
	else {
		$max_post = $_GET['page_size'];	
	}
	
	
    $num_post = count($products); //количество постов всего


    $num_pages = intval(($num_post-1)/$max_post) +1;   //количество страниц которое будет отображаться
	

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ($page < 1) {
		$page = 1;
	}
    else 
		if ($page > $num_pages)
			$page = $num_pages;
}
else {
    $page = 1;
}

$current_element=0;
if ($products) {
	foreach($products as $item)	{
		$current_element++;

			if(($current_element>($page*$max_post-$max_post)) && ($current_element<=$page*$max_post)) {		
	?>
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
	<?
			}
	}
}
?>

<div style="clear:both"></div>
<div style='padding: 5px 15px; margin: 10px 0; border-radius: 15px; background-color: #8CBF00;'> </div>
   
 
<?
	// вывод навигации по страницам
	
	$page_url = $_GET['route'];			// url для перехода на новую страницу
	if (!empty($_GET['page_size']))	
		$page_url = $page_url  . '?page_size=' . $max_post . '&page=';
	else
		$page_url = $page_url . '?page=';
?>

<div class="pages_block">

<?
if ($num_pages > 2) {

	if ($page == 1) {
?>				
		<div class="navigation_big_button">		<a href='/<?=$page_url?>1'> Первая </a>						</div>
		<div class="navigation_push">			<a href='/<?=$page_url?>1'> 1 </a>							</div>
		<div class="navigation">				<a href='/<?=$page_url?>2'> 2 </a>							</div>		
		<div class="navigation">				<a href='/<?=$page_url?>3'> 3 </a>							</div>
		<div class="navigation_big_button">		<a href='/<?=$page_url?><?=$num_pages ?> '>  Последняя </a>	</div>
<?	
	}
	
	if ($page > 1 && $page < $num_pages) {
?>
		<div class="navigation_big_button">	<a href='/<?=$page_url?>1'> Первая </a>						</div>		
		<div class="navigation">			<a href='/<?=$page_url?><?=$page-1?>'> <?=$page-1?> </a>	</div>	
		<div class="navigation_push">		<a href='/<?=$page_url?><?=$page?>'> <?=$page?> </a>		</div>		
		<div class="navigation">			<a href='/<?=$page_url?><?=$page+1?>'> <?=$page+1?> </a>  	</div>
		<div class="navigation_big_button">	<a href='/<?=$page_url?><?=$num_pages ?> '>  Последняя </a>	</div>				
<?
	}
					
	if($page == $num_pages){
?>
		<div class="navigation_big_button">	<a href='/<?=$page_url?>1'> Первая </a>						</div>		
		<div class="navigation">			<a href='/<?=$page_url?><?=$page-2?>'> <?=$page-2?> </a>	</div>	
		<div class="navigation">			<a href='/<?=$page_url?><?=$page-1?>'> <?=$page-1?> </a>	</div>		
		<div class="navigation_push">		<a href='/<?=$page_url?><?=$page?>'> <?=$page?> </a>		</div>
		<div class="navigation_big_button">	<a href='/<?=$page_url?><?=$num_pages ?> '>  Последняя </a>	</div>	
<?
	}				
}
else 
	if($num_pages == 2) {
		if($page == 1){
?>
			<div class="navigation_big_button">	<a href='/<?=$page_url?>1'> Первая </a>						</div>		
			<div class="navigation_push">		<a href='/<?=$page_url?>1'> 1 </a>							</div>	
			<div class="navigation">			<a href='/<?=$page_url?>2'> 2 </a>							</div>		
			<div class="navigation_big_button">	<a href='/<?=$page_url?><?=$num_pages ?> '>  Последняя </a>	</div>
<?
		}
		else {
?>
			<div class="navigation_big_button">	<a href='/<?=$page_url?>1'> Первая </a>						</div>		
			<div class="navigation">		<a href='/<?=$page_url?>1'> 1 </a>								</div>	
			<div class="navigation_push">			<a href='/<?=$page_url?>2'> 2 </a>						</div>		
			<div class="navigation_big_button">	<a href='/<?=$page_url?><?=$num_pages ?> '>  Последняя </a>	</div>
<?
		}
	}
?>
</div>

<div style="text-align: center;">всего товаров: <?=count($products)?></div>

<?
}	// end of else
?> 	

