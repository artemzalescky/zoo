
// ====================================================================================
// 		инициализация сворачиваемого каталога
//		для работы необходимо подключить библиотеку для работы с классами classlib.js
// ====================================================================================

function initTreeMenu() {	// настройка действий меню
	var top = getElementsByClass('top_tree_submenu', document)[0];

	top.onmousedown = top.onselectstart = function() {	// отключаем выделение текста
		return false;
	}

	top.onclick = function(event) {	// вешаем обработчик на вершину дерева (делегирование)
		event = event || window.event;					// IE8-
		var target = event.target || event.srcElement;	// IE8-

		if (hasClass(target, 'icon') || hasClass(target, 'title')) 	// надо раскрыть
			toggleList(target.parentNode);
	}
}

function toggleList(list) {	// переключатель
	if (isClosed(list))		showList(list);
	else					hideList(list);
}

function showList(list) {	// показать список
	removeClass(list, 'closed_submenu');
}

function hideList(list) {	// скрыть список
	addClass(list, 'closed_submenu');
}

function isClosed(list) {	// скрыт ли список
	return hasClass(list, 'closed_submenu') ? true : false;		// если ничего нет, то считаем, что отрыто
}


// ====================================================================================
// 		функции создания новой таблицы
// ====================================================================================

function createNewAttribute() {
	var buttons = document.getElementById('add_remove_panel');
	buttons.insertAdjacentHTML("beforeBegin", 
					"<tr class='attribute_class'>\
						<td> <input type='text' onblur='appendNameToAttribute(this)' required> </td>\
						<td>\
							<select>\
								<option value = 'int' selected> Число </option>\
								<option value = 'varchar'> Строка </option>\
								<option value = 'text'> Текст </option>\
							</select>\
						</td>\
					</tr>");
}

function removeNewAttribute() {		// удаление последнего пользовательского поля таблицы
	var attr_arr = getElementsByClass('attribute_class', document);

	if (attr_arr.length > 0) {
		var attr = attr_arr[attr_arr.length-1];
		attr.parentNode.removeChild(attr);
	}
}

function appendNameToAttribute(element) {		// делает имя input и соотв select из value
	var select = element.parentNode.parentNode.children[1].children[0];		// элемент select (находим из input

	if (element.value) {
		element.name = 'createtable_' + element.value;
		select.name = 'createtabletype_' + element.value;
	}
	else {
		element.name = '';
		select.name = '';
	}
}