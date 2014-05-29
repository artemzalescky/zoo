<?php

class Application_Models_Product {    
 
	function getProduct() {		// получить информацию о конкретном продукте для вывода в View
	
		$route_arr =  explode('/',$_GET['route']);	
		$url_catalog = $route_arr[1];
		$id_product = $route_arr[2];

		$name_table = getCatalogNameFromURL($url_catalog);		// получаем имя таблицы с товаром
		

		$sql = "SELECT * FROM $name_table WHERE id = '$id_product'";
		$result = mysql_query($sql)  or die(mysql_error());

		if($row = mysql_fetch_object($result)){
			foreach ($row as $i => $v) {
				$product["$i"] = $v;			// создаем в результате все атрибуты продукта
			}
		}
		
		$product['picture_url'] = getProductImagesURL($name_table) . '/' . $product['id'] . '.jpg';		// путь к картинке продукта	
		$product['catalog_tree'] = getCatalogParents($name_table);										// Каталог > Ванны > ...

		
		//	## ассоциативный массив соответствия английских и русских имен аттрибутов
		//	если у аттрибута есть русское соответствие (оно не пусто и не равно #), то выводим его
		//	если соответстие = '#' , то данный аттрибут технический и не предназначен для вывода
		//	если соответствия нет, то просто выводим значение поля без русского названия (description)
		
		$product['eng_rus'] = array (
			'description' => 'Описание',
			'producer' => 'Производитель',
			'size' => 'Размер',
			'thickness' => 'Толщина',
			'glubina' => 'Глубина',
			'color' => 'Цвет',
			'diametr' => 'Диаметр',
			'length' => 'Длина',
			'material' => 'Мателиал',
			'model' => 'Модель',
			'weight' => 'Вес',
			'age' => 'Возраст',
			'for' => 'Особенности',
			'id' => '#',					// # - технические аттрибуты либо выводим отдельно (типа название, цена)
			'name' => '#',
			'id_catalog' => '#',
			'price' => '#',
			'picture_url' => '#',
			'catalog_tree' => '#',
			'existence' => '#',
			'eng_rus' => '#'
		);
			
		return $product;
	}
}
 
 
function getCatalogNameFromURL($url) {		// 	получить поле name каталога по его url
	$name = null;

	$sql = "SELECT name FROM catalog where url = '$url'";
	$result = mysql_query($sql);				
	if($row = mysql_fetch_object($result)) {	// если нашли					
			$name = $row->name;					
	}
			
	return $name;	
}

function getProductImagesURL($name_catalog) {		// получить путь к картинкам продуктов каталога с именем $name_catalog
	$url = 'images';
	$name_catalog = explode('_',$name_catalog);
	
	for ($i = 1; $i < count($name_catalog); $i++) {			
		$sql = "SELECT url FROM catalog where id = '{$name_catalog[$i]}'";
		$result = mysql_query($sql);
			
		if($row = mysql_fetch_object($result)) {	// если нашли					
			$url = $url.'/'.$row->url;					
		}
	}

	return $url;
}

function getCatalogParents($name_table) {		// массив предков данного каталога (массивы из url и русского имени)
	$temp_arr = explode('_', $name_table);
	$res[0]['url'] = 'catalog';
	$res[0]['name_rus'] = 'Каталог';
	for ( $i = 1; $i < count($temp_arr); $i++) {
		$sql = "SELECT * FROM catalog WHERE id='{$temp_arr[$i]}'";
		$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
		if ($row = mysql_fetch_object($result)) {	// если нашли
			$res[$i]['url'] = 'catalog/' . $row->url;
			$res[$i]['name_rus'] = $row->name_rus;
		}
	}
	
	return $res;
}

?>