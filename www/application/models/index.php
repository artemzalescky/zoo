<?
	// модель главной 

	class Application_Models_Index  extends Lib_BaseController {
	
		function getMainMenuCatalogs() {		// каталоги, отображаемые в главном меню
			$catalogs = null;
			
			$res = mysql_query("SELECT * FROM catalog WHERE name LIKE 'product\_%' AND name NOT LIKE 'product\_%\_%'");		// все каталоги парвого уровня (типа product_1)
			while ($row = mysql_fetch_object($res)) {
			
				$mainCatalog = array (		// главный каталог (типа "Для собак")
					'nameRus' => $row->name_rus,
					'url' => $row->url
				);
				
				$kids = null;
				
				$res2 = mysql_query("SELECT * FROM catalog WHERE name LIKE '{$row->name}\_%' AND name NOT LIKE '{$row->name}\_%\_%'");	// 2-й уровень каталогов
				while ($row2 = mysql_fetch_object($res2)) {
					$kids[] = array (
						'nameRus' => $row2->name_rus,
						'url' => $row2->url
					);
				}

				$catalogs[] = array (
					'mainCatalog' => $mainCatalog,
					'kids' => $kids
				);
			}

			return $catalogs;
		}
	
		function getPopularProducts() {		// достаем продукты категории "Популярное"
			$products = null;

			$res = mysql_query("SELECT * FROM product_popular");	// достаем id и id_catalog популярных товаров
			
			while ($row = mysql_fetch_object($res)) {
				$res_table_name = mysql_query("SELECT * FROM catalog WHERE id='{$row->id_catalog}'");	// имя таблицы с товаром
				
				if ($row_table_name = mysql_fetch_object($res_table_name)) {

					$res_product = mysql_query("SELECT * FROM {$row_table_name->name} WHERE id='{$row->id_product}'");	// сам товар
					if ($row_product = mysql_fetch_object($res_product)) {
						$picture_url = $this->getProductImagesURL($row_table_name->name);		// путь к картинкам данного каталога
						$product_url = $this->getCatalogURLFromName($row_table_name->name);		// поле url текущего каталога

						$products[] = array(
							"id" => $row_product->id,
							"id_catalog" => $row_product->id_catalog,
							"name" => $row_product->name,
							"price" => $row_product->price,
                            "weight" => $row_product->weight,
							"short_descr" => $row_product->for,
							"picture_url" => $picture_url.'/'.$row_product->id.'.jpg',			// имя картинки с путем к ней
							"product_url" => 'product/'.$product_url.'/'.$row_product->id		// URL для просмотра данного продукта
						);
					}
				}
			}
			
			return $products;
		}
		
		function getSaleProducts() {		// достаем продукты категории "Акция"
			$products = null;

			$res = mysql_query("SELECT * FROM product_sale");	// достаем id и id_catalog популярных товаров
			
			while ($row = mysql_fetch_object($res)) {
				$res_table_name = mysql_query("SELECT * FROM catalog WHERE id='{$row->id_catalog}'");	// имя таблицы с товаром
				
				if ($row_table_name = mysql_fetch_object($res_table_name)) {

					$res_product = mysql_query("SELECT * FROM {$row_table_name->name} WHERE id='{$row->id_product}'");	// сам товар
					if ($row_product = mysql_fetch_object($res_product)) {
						$picture_url = $this->getProductImagesURL($row_table_name->name);		// путь к картинкам данного каталога
						$product_url = $this->getCatalogURLFromName($row_table_name->name);		// поле url текущего каталога

						$products[] = array(
							"id" => $row_product->id,
							"id_catalog" => $row_product->id_catalog,
							"name" => $row_product->name,
							"price" => $row_product->price,
                            "weight" => $row_product->weight,
							"short_descr" => $row_product->for,
							"picture_url" => $picture_url.'/'.$row_product->id.'.jpg',			// имя картинки с путем к ней
							"product_url" => 'product/'.$product_url.'/'.$row_product->id		// URL для просмотра данного продукта
						);
					}
				}
			}
			
			return $products;
		}
		
		// private
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
	}
?>