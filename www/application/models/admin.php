<?php

	// Модель вывода категории товаров
	
	class Application_Models_Admin {	

		function getAllCatalogs() {		// информация о всех каталогах (1)
			$sql = "SELECT * FROM catalog";	// current_url - массив разбитого url
			$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
				
			while ($row = mysql_fetch_object($result)) {	// если нашли
				
				$catalogs[] = array( "id" => $row->id,							// Array(id, имя, url, русское имя,id_parent)		
									 "id_parent" => getIdParent($row->name),
									 "name" => $row->name,
									 "url" => $row->url,
									 "name_rus" => $row->name_rus);  
			}

			return $catalogs;
		}
		
		function createNewTable() {
			$fields = null;		// поля новой таблицы
			foreach ($_GET as $i => $v) {
				if (strpos($i,'createtable_') === 0) {
					$name = $v;
					$type = $_GET['createtabletype_'.$v];

					$fields[] = array(
						'name' => $name,
						'type' => $type
					);
				}
			}
			
			$table_name = $this->getName($_GET['new_table_id']);
			
			if ($table_name) {		// запрос на создание таблицы
				$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `id` int(11) NOT NULL DEFAULT '0',
					  `name` varchar(100) NOT NULL,
					  `id_catalog` int(11) NOT NULL,
					  `price` varchar(20) DEFAULT NULL,";
  
				if ($fields) {
					foreach ($fields as $i => $v) {
						if ($v['type'] == 'int')
							$sql = $sql . "`{$v['name']}` int(11),";
						if ($v['type'] == 'varchar')
							$sql = $sql . "`{$v['name']}` varchar(100),";
						if ($v['type'] == 'text')
							$sql = $sql . "`{$v['name']}` text,";
					}
				}
				
				$sql = substr($sql,0,strlen($sql)-1);		// убираем лишнюю запятую
				$sql = $sql . ')DEFAULT CHARSET=utf8;';
				
				$result = mysql_query($sql);
				
				if ($result) {
					header("Location: /admin");
				}
			}
		}
		
		function getCatalogInfo($id) {	// получить инфомацию о данном каталоге (3)
			$info = null;
			
			if ($id == 0) {
				$info['id'] = 0;
				$info['name'] = 'product';
				$info['url'] = 'catalog';
				$info['name_rus'] = 'Каталог';
			}
			
			$sql = "SELECT * FROM catalog WHERE id='$id'";
			$result = mysql_query($sql) or die(mysql_error());
			
			if ($row = mysql_fetch_object($result)) {
				$info['id'] = $row->id;
				$info['name'] = $row->name;
				$info['url'] = $row->url;
				$info['name_rus'] = $row->name_rus;
				$info['new_subcatalog_id'] = null;		// предполагаемый id сына
			}

			
			// нахождение приемлемого id для нового подкаталога
			$tmp_id = 10*$info['id'] + 1;						// предполагаемый id (т.е. если родительского 45, то начинаем с 451)
			do {
				$sql = "SELECT * FROM catalog WHERE id='$tmp_id'";
				$result = mysql_query($sql) or die(mysql_error());
				
				if ($row = mysql_fetch_object($result))			// если такой id уже есть
					$tmp_id++;									// будем пробовать следующий
				else
					$info['new_subcatalog_id'] = $tmp_id;		// нашли
	
			} while($info['new_subcatalog_id'] == null);
			
			// имя нового каталога (клеим из имени прошлого и new_subcatalog_id)
			$info['new_subcatalog_name'] = $info['name'] . '_' . $info['new_subcatalog_id'];

			return $info;
		}

		function createNewCatalog($id, $name, $url, $name_rus) {	// создать новый каталог (3, шаг 2)
			$logs = null;	// информация об успешности создания таблицы или об ошибках
			//echo $id.' '.$name.' '.$url.' '.$name_rus.'<br> ';
			
			// сначала проверяем на уникальность id и url
			$sql = "SELECT * FROM catalog WHERE id='$id'";
			$result = mysql_query($sql) or die(mysql_error());
			if ($row = mysql_fetch_object($result))				// если такой id уже есть
				$logs['id'] = 'Поле "id" должно быть уникальным';


			$sql = "SELECT * FROM catalog WHERE url='$url'";
			$result = mysql_query($sql) or die(mysql_error());
			if ($row = mysql_fetch_object($result))				// если такой id уже есть
				$logs['url'] = 'Данный URL-адрес уже существует';
			
			// проверка на пустоту полей url и name_rus
			if (empty($name_rus))				// если такой id уже есть
				$logs['name_rus'] = 'Поле "Имя" должно быть заполнено';
			if (empty($url))				// если такой id уже есть
				$logs['url'] = 'Поле "URL-адрес" должно быть заполнено';
				
			if ($logs == null) {	// все параметры корректные
				$logs['success'] = true;
				$sql = "INSERT INTO catalog (id,name,url,name_rus) VALUES ('$id','$name','$url','$name_rus')";
				$result = mysql_query($sql) or die("Невозможно создать подкаталог: ".mysql_error());		// заносим подкаталог в базу
			}
			else
				$logs['success'] = false;	// были ошибки, возвращаем на заполнение формы
		
			return $logs;
		}
		
		function deleteCatalog($id, $name) {	// удалить каталог со всеми детьми и всеми таблицами товаров (4.2)
			$logs = null;	// информация об успешности создания таблицы или об ошибках
			
			$sql = "SELECT * FROM catalog WHERE id='$id'";
			$result = mysql_query($sql) or die(mysql_error());
			if (!($row = mysql_fetch_object($result)))				// если такой id уже есть
				$logs['id'] = 'Такой каталог не найден';
			
			if ($logs == null) {	// все параметры корректные
				$logs['success'] = true;
				
				$name_arr = explode('_',$name);		// разбиваем имя, т.к. удаление продуктов отличается для труб, соединений и остальных
			
				if (count($name_arr) > 1) {			// не верхний каталог
					// находим всех детей текущего для удаления
					$sql = "SELECT * FROM catalog WHERE name LIKE '{$name}\_%'";
					$result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД
					while ($row = mysql_fetch_object($result)) {	// если нашли					
						mysql_query("DELETE FROM catalog WHERE id='{$row->id}'") or die("Невозможно удалить каталог: ".mysql_error());	// удаляем все подкаталоги									
						mysql_query("DROP TABLE {$row->name}");																			// удаляем таблицы с товарами
					}
	
					mysql_query("DELETE FROM catalog WHERE id='$id'") or die("Невозможно удалить каталог: ".mysql_error());	// удаляем запись из каталогов																
					mysql_query("DROP TABLE $name");																		// удаляем таблицу с товарами
				}
			}
			else
				$logs['success'] = false;	// были ошибки, возвращаем на заполнение формы
		
			return $logs;
		}
		
		function getNameRusParent($name) {		// имя родительского каталога
			$id_parent = getIdParent($name);
			if ($id_parent == 0)
				return 'Каталог';
			$sql = "SELECT * FROM catalog WHERE id='$id_parent'";
			$result = mysql_query($sql);
			if ($row = mysql_fetch_object($result))				// если такой id уже есть
				return $row->name_rus;			
			return null;
		}
		
		function getName($id) {		// имя каталога
			$sql = "SELECT * FROM catalog WHERE id='$id'";
			$result = mysql_query($sql);
			if ($row = mysql_fetch_object($result))
				return $row->name;			
			return null;
		}
		
		// =====================================================================================================================
		
		 /*
         * получение всех свойств продуктов
         * */
		function getAllProducts($id) {
			$products = 'no_table';

			$name_table = null;
            $name = null;

            $sql = "SELECT * FROM catalog WHERE id='$id'";
            $result = mysql_query($sql) or die(mysql_error());	// посылаем запрос в БД

            if ($row = mysql_fetch_object($result)) {
                $name = $row->name;

				$name_table = $name;
				$sql = "SELECT * FROM $name_table";
				$result = mysql_query($sql);	// посылаем запрос в БД

				if ($result) {
					$products = null;
					while ($row = mysql_fetch_object($result)) {
					
						$p = null;
						foreach ($row as $i => $v) {
							$p["$i"] = $v;			// создаем в результате все атрибуты продукта
						}
						$products[] = $p;
					}
				}            
		    }

			return $products;
        }

        /*
         * сопоставление русских и английских фраз
         * */
        function getAssosiateEnglishRussian(){
            $arr = array (			// ## ассоциативный массив соответствия английских и русских имен аттрибутов
                'producer' => 'Производитель',
                'size' => 'Размер',
                'thickness' => 'Толщина',
                'glubina' => 'Глубина',
                'color' => 'Цвет',
                'diametr' => 'Диаметр',
                'length' => 'Длина',
                'material' => 'Мателиал',
                'description' => 'Описание',
                'name' => 'Имя',
                'id' => 'id',
                'id_catalog' => 'id_catalog',
                'id_class' => 'id_class',
                'price' => 'цена'
            );
			return $arr;
        }


        /*
         * получение значений товара по id и id_catalog получаем name
         * name является именем таблицы конечного товара;
         * */
		function getProductInfo($id, $id_catalog) {
			$name_table = null;

			$sql = "SELECT * FROM catalog WHERE id='$id_catalog' ";	//
			$result = mysql_query($sql) or die(mysql_error());	//

			if ($row = mysql_fetch_object($result)) {	//
				$name_table=$row->name;

				$sql = "SELECT * FROM $name_table WHERE id='$id' ";	//
				$result = mysql_query($sql) or die(mysql_error());	//

				if ($row = mysql_fetch_object($result)) {
					$p = null;
					foreach ($row as $i => $v) {
						$p["$i"] = $v;			// создаем в результате все атрибуты продукта
					}
					$products = $p;
				}
			}
			return $products;
		}

        /*
         * корректировка данных в базе данных
         *
         * необходимо сначала удалить строку, а затем занести вновь.
         * */
        function changeDateProduct(){

            $mas_data = null;  //ассоциативный массив в котором будут храниться передаваемые параметры для запии в таблицу.

            /**/
            foreach($_GET as $i => $v){
                if (strpos($i,'change_product') === 0){
                    $attribute_name = substr($i,strlen('change_product_'));
                    //echo $attribute_name.'='.$v;
                    //echo "<br>";
                    $mas_data["$attribute_name"] = $v;
                }
            }

            $log = null; // информация об успешности создания таблицы или об ошибках

            //проверим корректность введённых данных
            // проверка на пустоту полей name
//            if (empty($name))				// если такой id уже есть
//                $log['name'] = 'Поле "Имя" должно быть заполнено';

            if ($log == null) {	// все параметры корректные
                $log['success'] = true;
                $log['id_catalog'] = $mas_data['id_catalog'];

                //ищем название таблицы где хранится такой элемент, поиск осуществляется в каталоге
                $sql = "SELECT * FROM catalog WHERE id='{$mas_data['id_catalog']}' ";	//
                $result = mysql_query($sql) or die(mysql_error());	//

                if ($row = mysql_fetch_object($result)) {
                    $name_table = $row->name;

                    //удалить элемент из таблицы
                    $sql_delete = "DELETE FROM $name_table WHERE id='{$mas_data['id']}' " ;
                    $result = mysql_query($sql_delete) or die(mysql_error());


                    //добавить элемент в таблицу
                    $sql = "INSERT INTO $name_table (";        // INSERT INTO $name_table (id,name,id_catalog,.....)
                          foreach($mas_data as $i => $v){
                            $sql=$sql."$i,";
                          }
                           $sql = substr($sql,0,strlen($sql)-1);     //убираем последнюю запятую
                           $sql = $sql.") VALUES (";

                            foreach($mas_data as $i => $v){
                                $sql=$sql."'$v',";
                            }

                            $sql = substr($sql,0,strlen($sql)-1);     //убираем последнюю запятую
                            $sql = $sql.")";
                    $result = mysql_query($sql) or die("Невозможно добавить товар: ".mysql_error());		// заносим подкаталог в базу
                }
            }
            else
                $log['success'] = false;	// были ошибки, возвращаем на заполнение формы


            return $log;
        }

		
        /*
         * удаление продукта из таблицы товаров
         *
         *
         * */


        function deleteProduct($id,$id_catalog){
            $log = null; // информация об успешности создания таблицы или об ошибках

            if ($log == null) {	// все параметры корректные
                $log['success'] = true;
                $log['id_catalog'] = $id_catalog;

                $sql = "SELECT * FROM catalog WHERE id='$id_catalog' ";	//
                $result = mysql_query($sql) or die(mysql_error());	//

                if ($row = mysql_fetch_object($result)) {
                    $name_table = $row->name;

                    //удалить элемент из таблицы
                    $sql_delete = "DELETE FROM $name_table WHERE id='$id' " ;
                    $result = mysql_query($sql_delete) or die(mysql_error());

                };

            }

            return $log;
        }

        function getAttributeNewProduct($id_catalog) {		// получить все аттрибуты из таблицы

            //ищем по $id_catalog name;
            $sql = "SELECT * FROM catalog WHERE id='$id_catalog' ";
            $result = mysql_query($sql) or die(mysql_error());

            if ($row = mysql_fetch_object($result)) {
                $name_table = $row->name;

				$sql = "SHOW FIELDS FROM $name_table";	//  из данной таблицы получаем аттрибут
                $result = mysql_query($sql) or die(mysql_error());	//

				while ($row = mysql_fetch_object($result)) {
					$products[] = $row->Field;
				}
            }
			
			return $products;
        }

        /*получить максимальный используемый id в таблице товаров*/
        function getLastNumberId($id_catalog){

            //получим название таблицы в которой лежат искомые товары
            $sql = "SELECT * FROM catalog WHERE id = '$id_catalog' ";	//
            $result = mysql_query($sql) or die(mysql_error());	//

            if ($row = mysql_fetch_object($result)) {
                $name_table = $row->name;

                $sql = "SELECT * from $name_table WHERE id = (SELECT max(id) FROM $name_table)";
                $result = mysql_query($sql) or die(mysql_error());

                if ($row = mysql_fetch_object($result)) {
                    $id = $row->id;
                }

            }
            return $id;
        }


        //добавить новый товар в таблицу
		function createProduct(){

            $logger = null; // информация об успешности создания таблицы или об ошибках
            $mas_attributes = null;  //ассоциативный массив в котором будут храниться передаваемые параметры для запии в таблицу.
            $masType = null;  //массив в котором будет храниться имя поля и его тип
            $name_table = null;  //конечная таблица

            /*получаем массив атрибутов или полей таблицы*/
            foreach($_GET as $i => $v){
                if (strpos($i,'create_product_') === 0){
                    $attribute_name = substr($i,strlen('change_product_'));
                    $mas_attributes["$attribute_name"] = "$v";
                }
            }

            $sql = "SELECT * FROM catalog WHERE id = '{$_GET['create_product_id_catalog']}' ";
            $result = mysql_query($sql) or die(mysql_error());	//

            if ($row = mysql_fetch_object($result)) {
                $name_table = $row->name;

                /*создадим массив в котором будет имя поля и его тип*/
                $sql = "SHOW FIELDS FROM $name_table";
                $result = mysql_query($sql);

                while ($row = mysql_fetch_object($result)) {
                    $a = null;
                    $a = explode('(',$row->Type);   //разделить cтроку до символа ( и после       сама строка int(11)

                    //в строке записаны типы в такой форме int(11) нужно чтобы осталось int
                    //причем если type = text ничего обрезать не требуется
                    if ($a == null){
                        $masType["$row->Field"] = "$row->Type";
                    } else{
                        $masType["$row->Field"] = "$a[0]";
                    }
                }
            }

            if ($logger == null) {	// все параметры корректные
                $logger['success'] = true;
                $logger['id_catalog'] = $_GET['create_product_id_catalog'];

                //echo $_GET['create_product_id_catalog'];

                //ищем название таблицы где хранится такой элемент, поиск осуществляется в каталоге
                $sql = "SELECT * FROM catalog WHERE id='{$_GET['create_product_id_catalog']}' ";	//
                $result = mysql_query($sql) or die(mysql_error());	//

                if ($row = mysql_fetch_object($result)) {
                    $name_table = $row->name;

                    //добавить элемент в таблицу
                    $sql = "INSERT INTO $name_table (";        // INSERT INTO $name_table (id,name,id_catalog,.....)
                    foreach($mas_attributes as $i => $v){
                        $sql=$sql."$i,";
                    }
                    $sql = substr($sql,0,strlen($sql)-1);     //убираем последнюю запятую
                    $sql = $sql.") VALUES (";

                    foreach($mas_attributes as $i => $v){
                        $sql=$sql."'$v',";
                    }

                    $sql = substr($sql,0,strlen($sql)-1);     //убираем последнюю запятую
                    $sql = $sql.")";
                    $result = mysql_query($sql) or die("Невозможно добавить товар: ".mysql_error());		// заносим подкаталог в базу
                }
            }
            else
                $logger['success'] = false;	// были ошибки, возвращаем на заполнение формы

            return $logger;
        }
	}

	function getIdParent($name) {		// вернет предпоследнее число в имени каталога
		$arr = explode('_',$name);
		if (count($arr) <= 2 ) 
			return 0;
		return $arr[count($arr)-2];
	}

	function echoCatalog($id, $catalogs) {	// вывод каталога и его детей (рекурсия)
		$current_catalog = null;		// информация о текущем каталоге
		foreach ($catalogs as $i => $v) {
			if ($v['id'] == $id) {
				$current_catalog['name'] = $v['name'];
				$current_catalog['url'] = $v['url'];
				$current_catalog['name_rus'] = $v['name_rus'];
				$current_catalog['id'] = $v['id'];
			}
		}
		
		if ($id == 0) {
			$current_catalog['name'] = 'product';
			$current_catalog['url'] = 'catalog';
			$current_catalog['name_rus'] = 'Каталог';
			$current_catalog['id'] = 0;
		}
		
		$kids = getKids($id, $catalogs);
		
		if ($id == 0)
			echo "<div id='catalog_0' class='tree_submenu top_tree_submenu'>";
		else
			echo "<div id='catalog_$id' class='tree_submenu'>";
		
		if ($kids != null) {
			echo "<div class='icon'></div>";		// есть дети => иконка
			echo "<span class='title'> {$current_catalog['name_rus']}</span> ";
		}
		else {		// пока продукты можно просмотреть только для листьев
			echo "<span><a href='/admin?id_catalog={$current_catalog['id']}'> {$current_catalog['name_rus']}</a></span> ";
		}
		
		echo "<a href='/admin?add_subcatalog=1&id_parent_catalog=$id' style='font-size: 10px;'>(подкаталог)</a> ";
		
		if ($id != 0)
			echo "<a href='/admin?delete_catalog=1&delete_catalog_id=$id' style='font-size: 10px;'>(удалить)</a>";
		
		if ($kids != null) {		// если есть дети, то рекурсия
			echo '<ul>';
			foreach ($kids as $i => $v) {
				echo '<li>';
				echoCatalog($v, $catalogs);
				echo '</li>';
			}
			echo '</ul>';
		}
		
		echo "</div>";
	}
	
	function getKids($id ,$catalogs) {		// получить детей каталога с таким id
		$res = null;
		
		foreach ($catalogs as $i => $v) {
			if ($v['id_parent'] == $id)
				$res[] = $v['id'];
		}
		
		return $res;
	}

?>