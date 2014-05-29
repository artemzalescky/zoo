<?php

//Модель вывода категории товаров
		
class Application_Models_Catalog {

	function getCatalogInfo() {
		$info=null;					// вся инфа о каталоге
								// 'name', 'id', 'url', 'name_rus'	- информация о текущем каталоге
								// 'catalog_image_url' - путь к картинкам подкаталогов
								// 'near_kids' - массив из url и названий ближайших детей  ( напр	$info['near_kids'][$i]['url'] )
								// 'kids' - массив названий таблиц продуктов всех детей
								// 'parents' - массив из url и названий родителей  
		
		$current_url = explode('/',$_GET['route']);
		
		
		// получаем информацию о текущем каталоге
		$sql = "SELECT * FROM catalog WHERE url='{$current_url[1]}'";	// current_url - массив разбитого url
		$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
		if ($row = mysql_fetch_object($result)) {	// если нашли
			$info['id'] = $row->id;
			$info['name'] = $row->name;
			$info['url'] = $row->url;
			$info['name_rus'] = $row->name_rus;
		}


		// получаем информацию о ближайших детях текущего каталога	(для выбора подкаталога)
		$info['near_kids'] = null;
		$sql = "SELECT * FROM catalog WHERE name LIKE '{$info['name']}\_%' AND name NOT LIKE '{$info['name']}\_%\_%'";	// шаблон (если cur_name = product_1 берем все product_1_% и чтоб больше слешей не было
		$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
		$i = 0;
		while ($row = mysql_fetch_object($result)) {	// если нашли
			$info['near_kids'][$i]['url'] = $row->url;
			$info['near_kids'][$i]['name_rus'] = $row->name_rus;
			$i++;
		}

		
		// получаем информацию о всех детях (для вывода продуктов)
		$info['kids'] = null;
		$sql = "SELECT * FROM catalog WHERE name LIKE '{$info['name']}\_%'";
		$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
		while ($row = mysql_fetch_object($result)) {	// если нашли
			$info['kids'][] = $row->name;
		}
		

		// получаем информацию о предках
		$temp_arr = explode('_', $info['name']);
		$info['parents'][0]['url'] = '';
		$info['parents'][0]['name_rus'] = 'Каталог';
		for ( $i = 1; $i < count($temp_arr); $i++) {
			$sql = "SELECT * FROM catalog WHERE id='{$temp_arr[$i]}'";
			$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
			if ($row = mysql_fetch_object($result)) {	// если нашли
				$info['parents'][$i]['url'] = $row->url;
				$info['parents'][$i]['name_rus'] = $row->name_rus;
			}
		}


		$info['catalog_image_url'] = '/images/catalog';
		for ( $i = 1; $i < count($info['parents']); $i++) {
			$info['catalog_image_url'] = $info['catalog_image_url'] . '/' . $info['parents'][$i]['url'];
		}
		
		return $info;
	}
	
	
	function getProducts($catalog_name, $kids) {	// достаем продукты для всех детей и самого каталога (с проверкой существования такой таблицы)
		$products = null;
		
		// достаем продукты текущего каталога
		$sql = "SELECT * FROM $catalog_name";
		$result = mysql_query($sql);
		$picture_url = getProductImagesURL($catalog_name);		// путь к картинкам данного каталога
		$product_url = getCatalogURLFromName($catalog_name);	// поле url текущего каталога
		
		if ($result) {	// если такая таблица есть
			while ($row = mysql_fetch_object($result)) {	// если нашли
                $description = spaceSubstr($row->for, 0, 150);
				if (strcmp($description,$row->for) != 0)
					$description .= '...';		// строка была обрезана
				
				$products[] = array(
					"id" => $row->id,
					"id_catalog" => $row->id_catalog,
					"name" => $row->name,
					"price" => $row->price,
					"weight" => $row->weight,
                    "for" => $row->for,
                    "short_descr" => $description,
					"picture_url" => $picture_url.'/'.$row->id.'.jpg',			// имя картинки с путем к ней
					"product_url" => 'product/'.$product_url.'/'.$row->id		// URL для просмотра данного продукта
				);
			}
		}
		
		// достаем продукты всех подкаталогов
		if ($kids) {
			foreach ($kids as $i => $v) {		// достаем продукты детей текущего каталога	
				$sql = "SELECT * FROM $v";
				$result = mysql_query($sql);
				$picture_url = getProductImagesURL($v); 		// путь к картинкам данного каталога
				$product_url = getCatalogURLFromName($v);		// поле url текущего каталога
				
				if ($result) {	// если такая таблица есть
					while ($row = mysql_fetch_object($result)) {	// если нашли
                        $description = spaceSubstr($row->for, 0, 150);
						if (strcmp($description,$row->for) != 0)
							$description .= '...';		// строка была обрезана
							
						$products[] = array(
							"id" => $row->id,
							"id_catalog" => $row->id_catalog,
							"name" => $row->name,
							"price" => $row->price,
							"weight" => $row->weight,
                            "for" => $row->for,
                            "short_descr" => $description,
							"picture_url" => $picture_url.'/'.$row->id.'.jpg',			// имя картинки с путем к ней
							"product_url" => 'product/'.$product_url.'/'.$row->id		// URL для просмотра данного продукта
						);
					}
				}
			}
		}
		
		return $products;
	}
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


function getCatalogURLFromName($name_catalog) {		// 	получить поле url каталога по его имени
	$url = '';
	
	$sql = "SELECT url FROM catalog where name = '$name_catalog'";
	$result = mysql_query($sql);				
	if ($row = mysql_fetch_object($result)) {	// если нашли					
		$url = $row->url;					
	}
			
	return $url;
}

function cout($message) {		// ## вывод текста (для отладки)
	echo $message . '<br>';
}

/*function spaceSubstr($str, $start, $length) {	// обрезает не длиннее length до последнего пробела
    $spaceLength = $length;
    $strCurrent = count($str);

    if ($strCurrent < $length)
        return $str;
    else{
        while ($spaceLength && $str[$start + $spaceLength - 1] != ' ')
            $spaceLength--;
        return substr($str, $start, $spaceLength);
    }
}*/
function spaceSubstr($str, $start, $length) {	// обрезает не длиннее length до последнего пробела
	if ($length >= strlen($str))	return $str;
	$space_length = $length;
	while ($space_length && $str[$start + $space_length - 1] != ' ')
		$space_length--;
	return substr($str, $start, $space_length);
}
?>