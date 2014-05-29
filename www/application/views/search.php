<?	if ($search_result) {	?>
		<h2 class='search_result_title'> Результаты поиска по запросу "<span style='color:#555'><?=$_GET['words']?></span>" </h2>
		
<?		foreach ($search_result as $i => $v) {	?>
			<a class='search_result_row' href='<?=$v['url']?>'> <?=$v['name']?></a> <br>
			<span style='margin-left:80px'> <?=$v['short_desc']?> </span><br><br>
<?		}
	}
	else {	?>
		<h2 class='search_result_title'> По вашему запросу ничего не найдено </h2>
<?	}	?>