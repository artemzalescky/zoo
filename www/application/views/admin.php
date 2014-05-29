<?

	echo "<div style='padding: 5px 15px; margin: 15px 0; border-radius: 15px; background-color: #777;'> </div>";
	
	// ==================================================================================
	// 		вывод дерева каталогов
	// ==================================================================================

	if (count($_GET) == 1) {				// если нет параметров кроме $_GET['route']
		echo "<div style='padding: 0 0 0 30px; color: #333;'><b>Администраторская панель</b> &nbsp&nbsp&nbsp&nbsp&nbsp<a href='/auth?logout=1' class='admin_link' style='font-size:14px'><b>Выйти</b></a></div>";
		echo "<hr style='margin-bottom:20px'>";
		echoCatalog(0, $catalogs);
		echo "<script> initTreeMenu(); </script>";
	}
	
	
	// ==================================================================================
	// 		добавление подкаталога
	// ==================================================================================
	
	if (isset($_GET['add_subcatalog'])) {
?>
		<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a> 
		<span> Создание нового подкаталога для <b> "<?=$parent_catalog['name_rus']?>" </b> </span><br><br><br>
		<form action='/admin' method='GET'>
			<table id='add_subcatalog_table'>
				<tr>
					<td> id </td> 
					<td> <input type='text' name='subcatalog_id' value='<?=$parent_catalog['new_subcatalog_id']?>' onKeyDown='return false;'> </td> 
				</tr>
				<tr>
					<td> name </td> 
					<td> <input type='text' name='subcatalog_name' value='<?=$parent_catalog['new_subcatalog_name']?>' onKeyDown='return false;'> </td> 
				</tr>
				<tr>
					<td> Имя </td> 
					<td> <input type='text' name='subcatalog_name_rus' autocomplete='off'> </td> 
				</tr>
				<tr>
					<td> URL-адрес </td> 
					<td> <input type='text' name='subcatalog_url' autocomplete='off'> </td> 
				</tr>
				<tr>
					<td colspan='2'> 
						<input type='submit' value='Создать подкаталог' name='add_subcatalog_add_button' id='add_subcatalog_add_button'> 
						<button id='add_subcatalog_cancel_button' onclick='location.href="/admin"; return false;'> Отмена </button>
					</td>
				</tr>
			</table>
		</form>
<?
	}
	if (isset($logs_add_subcatalog)) {		// запрос на добавление подкаталога (форма уже была заполнена)		
		if ($logs_add_subcatalog['success']) {		// успешное создание подкаталога
			echo "<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a>";
			echo "<span> Каталог  <b> \"".$_GET['subcatalog_name_rus']."\" </b> успешно создан. </span><br><br><br>";
		}
		else {						// были ошибки
?>
			<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a> 
			<span> Создание нового подкаталога для <b> "<?=$parent_catalog_name_rus?>" </b> </span><br><br><br>
			<form action='/admin' method='GET'>
				<table id='add_subcatalog_table'>
					<tr>
						<td> id </td> 
						<td> <input type='text' name='subcatalog_id' value='<?=$_GET['subcatalog_id']?>' onKeyDown='return false;'> </td>
						<? if (!empty($logs_add_subcatalog['id']))	
							echo "<td style='color:#d00'>" . $logs_add_subcatalog['id'] . "</td>"; ?>
					</tr>
					<tr>
						<td> name </td> 
						<td> <input type='text' name='subcatalog_name' value='<?=$_GET['subcatalog_name']?>' onKeyDown='return false;'> </td>
					</tr>
					<tr>
						<td> Имя </td>
						<td> <input type='text' name='subcatalog_name_rus' value='<?=$_GET['subcatalog_name_rus']?>' autocomplete='off'> </td>
						<? if (!empty($logs_add_subcatalog['name_rus']))	
							echo "<td style='color:#d00'>" . $logs_add_subcatalog['name_rus'] . "</td>"; ?>
					</tr>
					<tr>
						<td> URL-адрес </td> 
						<td> <input type='text' name='subcatalog_url' value='<?=$_GET['subcatalog_url']?>' autocomplete='off'> </td> 
						<? if (!empty($logs_add_subcatalog['url']))	
							echo "<td style='color:#d00'>" . $logs_add_subcatalog['url'] . "</td>"; ?>
					</tr>
					<tr>
						<td colspan='2'> 
							<input type='submit' value='Создать подкаталог' name='add_subcatalog_add_button' id='add_subcatalog_add_button'> 
							<button id='add_subcatalog_cancel_button' onclick='location.href="/admin"; return false;'> Отмена </button>
						</td> 
					</tr>
				</table>
			</form>
<?
		}
	}
	
	
	// ==================================================================================
	// 		удаление каталога
	// ==================================================================================
	
	if (isset($_GET['delete_catalog'])) {
?>
		<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a> 
		<span> Удаление каталога <b> "<?=$catalog['name_rus']?>" </b> </span><br><br><br>
		

		Подтвердите удаление каталога "<?=$catalog['name_rus']?>" вместе <u>со всеми его подкаталогами и товарами</u>: 
		
		<br><br>
	
		<button class='delete_catalog_button' onclick='location.href="/admin?delete_catalog_confirm=1&delete_catalog_id=<?=$catalog['id']?>&delete_catalog_name=<?=$catalog['name']?>&delete_catalog_name_rus=<?=$catalog['name_rus']?>"'> Удалить </button>
		<button class='delete_catalog_button' onclick='location.href="/admin"'> Отмена </button>

<?
	}
	if (isset($_GET['delete_catalog_confirm'])) {		// подтвердили удаление
		if ($logs_delete_catalog['success']) {		// успешное создание подкаталога
			echo "<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a>";
			echo "<span> Каталог  <b> \"".$_GET['delete_catalog_name_rus']."\" </b> успешно удален. </span><br><br><br>";
		}
		else {		// такой каталог не найден 
			echo "<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a> ";
			echo "<span> Каталог  <b> \"".$_GET['delete_catalog_name_rus']."\" </b> не найден! </span><br><br><br>";
		}
	}
?>


<!-- ============================================================================================= -->

<!--
	вывод таблицы с товарами
-->

<? if (isset($_GET['id_catalog'])){
	if ($products != 'no_table') {			// есть таблица 
?>
	<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a> 
    <a id="new_product" href='/admin?current_id_catalog=<?=$_GET['id_catalog']?>&create_product=1'>  Добавить новый товар </a>

    <h2 style="text-align: center">Список товаров</h2>
    <br>
        <form action="/admin" method="GET">	<!-- изменения количества будут обрабатываться в контроллере cart-->
            <!-- создаем шапку для таблицы -->
            <table id="cart_table">
                <tr>
        <?
                $v = $products[0];                       //получить первую строку массива

				if ($v) {
					foreach ($v as $attribute => $value) {      //и по этой строке получить название столбцов
						if (!empty($eng_rus_massive["$attribute"])){	// наличие атрибута в массиве

							?>
							<th> <?=$eng_rus_massive["$attribute"]?> </th>
				<?      }
					}
				}
            ?>
                 <th>изменение данных</th>
                 <th>удаление</th>
                </tr>

        <?
		if ($products) {
			foreach ($products as $i => $v) {
			?>
				<tr class="simple_row">

			 <?
				 foreach ($v as $attribute => $value) {
					if ($v) {	// если параметр задан, т.е. не ноль
						if (!empty($eng_rus_massive["$attribute"])){	// есть ли в массиве $eng_rus_massive такой атрибут
			 ?>
					<td style="text-align:left; color: #333;">
								<?= $value?>
								</td>
			 <?
						}
					}
			 }
			?>
				  <td> <a href='/admin?show_id_catalog=<?=$v['id']?> &id_class_catalog=<?=$v['id_catalog']?>&change_product=1'> изменение </a> </td>
				  <td> <a href='/admin?show_id_catalog=<?=$v['id']?> &id_class_catalog=<?=$v['id_catalog']?>&delete_product=1'> удаление </a></td>
				</tr>
			<?
			}
		}
        ?>
            </table>
        </form>
		
		<div style='padding: 15px 15px; margin: -25px 0 25px 0; border-radius: 15px; background-color: #7BA800;'> </div>

<?
	}
	else {		// надо создать новую таблицу для продуктов
?>
			<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a>
			<span>Для данного каталога необходимо создать таблицу товаров</span><br><br><br>
			
			<form action='/admin' method='GET'>
				<table id='add_product_new_table'>
					<tr>
						<td style='text-align:center'> Название поля товара (англ.) </td> 
						<td style='text-align:center'> Тип </td>
					</tr>
					<tr>
						<td> <input type='text' name='createtable_id' value='id' disabled> </td> 
						<td> <input type='text' name='createtabletype_id' value='Число' disabled style='width:62px'> </td> 
					</tr>
					<tr>
						<td> <input type='text' name='createtable_id_catalog' value='id_catalog' disabled> </td>
						<td> <input type='text' name='createtabletype_id_catalog' value='Число' disabled style='width:62px'> </td>
					</tr>
					<tr>
						<td> <input type='text' name='createtable_name' value='name' disabled> </td>
						<td> <input type='text' name='createtabletype_name' value='Строка' disabled style='width:62px'> </td>
					</tr>
					<tr>
						<td> <input type='text' name='createtable_price' value='price' disabled> </td>
						<td> <input type='text' name='createtabletype_price' value='Строка' disabled style='width:62px'> </td>
					</tr>
					<tr id='add_remove_panel'>
						<td colspan='2' style='padding-top:20px'>
							<input type='submit' value='Добавить поле' onclick='createNewAttribute(); return false;' style='width: 250px'>
							<input type='submit' value='Удалить поле' onclick='removeNewAttribute(); return false;' style='width: 120px'>
						</td>
					</tr>
					<tr>
						<td> <input type='submit' name='createtablebutton' value='Создать таблицу'> </td>
						<input type='hidden' name='new_table_id' value='<?=$_GET['id_catalog']?>'>
					</tr>
				</table>
			</form>
<?
		}
}
?>


<?  if ($_GET['change_product']){           //если нажали на ссылку -> выводим таблицу с товаром

?>

<!--Редактирование продукта-->

<form action='/admin' method='GET'>
	<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a>
    <table id = 'change_product_table'>
        <h2 style="margin-left:350px">Редактирование продукта</h2>
       <? foreach($product_info as $i=>$v) {
           ?>
       <!--Вывод полей в таблицу-->

<?       if($i == 'description'){ ?>
               <tr>
                   <td> <?=$i?> </td>
                   <td > <textarea rows="4" cols="32" name='change_product_<?=$i?>'> <?=$v?> </textarea> </td>
               </tr>
     <?} else  if($i == 'id'){ ?>
                    <tr>
                        <td> <?=$i?> </td>
                        <td > <input type='text' name='change_product_<?=$i?>' value='<?=$v?>' onkeydown="return false;"> </td>
                    </tr>

           <?} else  if($i == 'id_catalog'){ ?>
               <tr>
                   <td> <?=$i?> </td>
                   <td > <input type='text' name='change_product_<?=$i?>' value='<?=$v?>' onkeydown="return false;"> </td>
               </tr>
           <?} else  if($i == 'id_class'){ ?>
               <tr>
                   <td> <?=$i?> </td>
                   <td > <input type='text' name='change_product_<?=$i?>' value='<?=$v?>' onkeydown="return false;"> </td>
               </tr>
           <? } else {?>
        <tr>
            <td> <?=$i?> </td>
            <td > <input type='text' name='change_product_<?=$i?>' value='<?=$v?>'> </td>
        </tr>
        <?}
       }?>
        <!--Вывод кнопки в таблицу-->
        <tr>
            <td  colspan='2'> <input id="button_send_product"  type="submit" value="Отправить" name="button_change_data_product" id='button_change_data_product'> </td>
        </tr>
    </table>
</form>
<?
}
?>



    <!--Изменение значения товара-->
<?

if (isset($logs_change_product)) {		// запрос изменеие продукта
    if ($logs_change_product['success'] == false) {		// успешное изменеие продукта
        ?>
        <h2>Ошибка</h2>
    <?
    }
}
?>


<!--Добавление нового товара товара-->
<?
if ($_GET['create_product']) {
?>
	<a href='/admin' class='admin_link' style='float:right;'> Список каталогов </a> 
    <form action='/admin' method='GET'>
        <table id = 'add_new_product_table'>
        <h2 style="text-align: center">Добавление нового продукта</h2>
        <? foreach($attribute_new_product as $i=>$v) {
            ?>
            <!--Вывод полей в таблицу-->
<?          if ($v == 'id'){  ?>
                <tr>
                    <td> <?=$v?> </td>
                    <td > <input type='text' name='create_product_<?=$v?>' value="<?=$last_id+1?>" onKeyDown='return false;' style='width:400px'></td>
                </tr>
            <?} else if($v == 'id_catalog'){?>
                <tr>
                    <td> <?=$v?> </td>
                    <td > <input type='text' name='create_product_<?=$v?>'  value="<?=$_GET['current_id_catalog']?>" onKeyDown='return false;' style='width:400px'> </td>
                </tr>

            <?} else if($v == 'description'){?>
                <tr>
                    <td> <?=$v?> </td>
                    <td > <textarea rows="4" cols="32" name='create_product_<?=$v?>'  value="<?=$_GET['current_id_catalog']?>" style='width:418px; height:150px'> </textarea> </td>
                </tr>
             <?} else{?>
                <tr>
                    <td> <?=$v?> </td>
                    <td > <input type='text' name='create_product_<?=$v?>' style='width:400px'></td>
                </tr>
                <?}?>
        <?}?>
        <!--Вывод кнопки в таблицу-->
        <tr>
            <td> <input id="add_new_product_table_add_button"  type="submit" value="Добавить" name="button_new_product" > </td>
            <td><button id='add_new_product_table_cancel_button' onclick='location.href="/admin?id_catalog=<?=$_GET['current_id_catalog']?>"; return false;'> Отмена </button></td>
        </tr>
    </table>
    </form>

<?
}

echo "<div style='padding: 5px 15px; margin: 15px 0; border-radius: 15px; background-color: #777;'> </div>";

?>

