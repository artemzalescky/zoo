
// ==================================================================
//			вспомогательные функции для работы с классами
// ==================================================================
	
	function addClass(e, eClass) {		// добавить элементу e класс eClass
		var arr = e.className.split(' ');	// список всех текущих классов элемента
		for (var i = 0; i < arr.length; i++)
			if (arr[i] == eClass)	return false;	// если уже есть, то ничего не меняем

		if (e.className == '')		// не было никих классов
			e.className = eClass;
		else						// дописываем
			e.className += ' '+eClass;
		
		return true;
	}

	function removeClass(e, eClass) {		// удалить класс из элемента
		var arr = e.className.split(' ');
		var wasInList = false;	 // флажок для возвращения значения
		for (var i = 0; i < arr.length; i++)
			if (arr[i] == eClass){	// нашли
				arr.splice(i, 1);
				i--;
				wasInList = true;
			}
		e.className = arr.join(' ');
		return wasInList;
	}
	
	function hasClass(e, eClass) {			// есть ли класс
		var arr = e.className.split(' ');	// список всех текущих классов элемента
		for (var i = 0; i < arr.length; i++)
			if (arr[i] == eClass)
				return true;
		return false;
	}
	
	function toggleClass(e, eClass) {	// переключатель: если класс установлен то убирает, и наоборот
		if (hasClass(e, eClass)) {
			removeClass(e, eClass);
			return false;
		}
		addClass(e, eClass);
		return true;
	}
	
// ================================================================================================================
//		эмуляция функции getElementsByClassName (для совместимости IE8-)
// 		дефолтная ф имеет вид element.getElementsByClassName(classList)	classList - строка с классами через пробел
// 		эта реализация getElementsByClass(element, classList)
// ================================================================================================================
	
	var getElementsByClass;

	if (document.getElementsByClassName) {
		getElementsByClass = function(classList, node) {   
			return (node || document).getElementsByClassName(classList);
		}
	} 
	else {

		getElementsByClass = function(classList, node) {			
			var node = node || document,
			list = node.getElementsByTagName('*'), 
			length = list.length,  
			classArray = classList.split(/\s+/), 
			classes = classArray.length, 
			result = [], i,j;
			for(i = 0; i < length; i++) {
				for(j = 0; j < classes; j++)  {
					if(list[i].className.search('\\b' + classArray[j] + '\\b') != -1) {
						result.push(list[i]);
						break;
					}
				}
			}
		
			return result;
		}
	}