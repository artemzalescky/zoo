/*Работаем с формой в которой отображаются акционные и популярные товары!!!*/

$(document).ready(function() {
	$("#content > span").hide(); // Скрываем содержание
	$("#tabs li:first").attr("id","current"); // Активируем первую закладку
	$("#content > span:first").fadeIn(); // Выводим содержание

	$('#tabs a').click(function(e) {
		e.preventDefault();
		$("#content > span").hide(); //Скрыть все сожержание
		$("#tabs li").attr("id",""); //Сброс ID
		$(this).parent().attr("id","current"); // Активируем закладку
		$('#' + $(this).attr('title')).fadeIn(); // Выводим содержание текущей закладки
	});
});
