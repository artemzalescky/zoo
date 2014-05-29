<?
	// модель поиска

	class Application_Models_Search  extends Lib_BaseController {
	
		function search($words) {	// поиск в базе по фразе			
			$search_result = null;
			
			if ($words) {		// фраза не пустая
				$sql = "SELECT * FROM catalog";		// все таблицы с продуктами
				$result = mysql_query($sql);
				
				while ($row = mysql_fetch_object($result)) {	// цикл по каталогам
				
					// проверим наличие поля description
					$sql = "SELECT description FROM {$row->name}";
					$contains_desc = mysql_query($sql);
					if ($contains_desc)
						$sql = "SELECT * FROM {$row->name} WHERE name LIKE '%$words%' OR description LIKE '%$words%'";		// поиск в имени и описании товара
					else
						$sql = "SELECT * FROM {$row->name} WHERE name LIKE '%$words%'";										// поиск только в имени товара
			
					$result2 = mysql_query($sql);
					
					while ($result2 && $row2 = mysql_fetch_object($result2)) {		// цикл по конкретной таблице
						$description = spaceSubstr($row2->description, 0, 150) . '...';
						
						$search_result[] =array(
							'name' => $row2->name,
							'url' => '/product/' . $row->url . '/' . $row2->id,
							'short_desc' => $description
						);
					}
				}
			}
			
			return $search_result;
		}
	}
	
	function spaceSubstr($str, $start, $length) {	// обрезает не длиннее length до последнего пробела
		$space_length = $length;
		while ($space_length && $str[$start + $space_length - 1] != ' ')
			$space_length--;
		return substr($str, $start, $space_length);
	}
	
?>