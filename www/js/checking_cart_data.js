	// =================================================
	// 		проверка введенных пользователем данных
	// 		при оформлении заказа
	// =================================================
	
	
	function initChecking() {	// вешаем все необхоимые обработчики на ввод пользователя
		// инпуты
		var nameInput = document.getElementsByName('cart2_name')[0];
		var addressInput = document.getElementsByName('cart2_address')[0];
		var phoneInput = document.getElementsByName('cart2_phone')[0];
		var emailInput = document.getElementsByName('cart2_email')[0];
		
		// форма отправки
		var form = document.getElementById('cart2_form');

		// обработчики потери фокуса
		nameInput.onblur = function() {
			getElementsByClass('cart2_error')[0].innerHTML = checkUserName(nameInput.value);	// если были ошибки, выводим их
		}
		addressInput.onblur = function() {
			getElementsByClass('cart2_error')[1].innerHTML = checkUserAddress(addressInput.value);
		}
		phoneInput.onblur = function() {
			getElementsByClass('cart2_error')[2].innerHTML = checkUserPhone(phoneInput.value);
		}
		emailInput.onblur = function() {
			getElementsByClass('cart2_error')[3].innerHTML = checkUserEmail(emailInput.value);
		}
		
		// такая же проверка при отправке формы
		form.onsubmit = function() {
			var value1 = checkUserName(nameInput.value);
			var value2 = checkUserAddress(addressInput.value);
			var value3 = checkUserPhone(phoneInput.value);
			var value4 = checkUserEmail(emailInput.value);
			
			if (value1 || value2 || value3 || value4) {		// есть хоть одна ошибка
				getElementsByClass('cart2_error')[0].innerHTML = value1		// выводим ошибки
				getElementsByClass('cart2_error')[1].innerHTML = value2;
				getElementsByClass('cart2_error')[2].innerHTML = value3;
				getElementsByClass('cart2_error')[3].innerHTML = value4;
				return false;		// отменяем отправку
			}
		}
	}
	
	
	
	// функции проверки валидности введенных значений
	
	function checkUserName(name) {
		if (!name || name.length < 2)
			return 'Введите имя';
		if (name.length > 100)
			return 'Имя не должно содержать более 100 символов';
		return '';		// без ошибок
	}
	
	function checkUserAddress(address) {
		if (!address || address.length < 4)
			return 'Введите адрес';
		if (address.length > 100)
			return 'Имя не должно содержать более 100 символов';
		return '';
	}
	
	function checkUserPhone(phone) {
		if (!phone)
			return 'Введите телефон';
		
		var countNumbers = 0;	// число цифр в телефоне
		for (var i = 0; i < phone.length; i++)
			if (phone[i].charCodeAt()>=48 && phone[i].charCodeAt()<=57)
				countNumbers++;
				
		if (countNumbers < 7)
			return 'Телефон должен содержать не менее 7-ми цифр';
		
		return '';
	}
	
	function checkUserEmail(email) {
		if (!email)
			return 'Введите электронный адрес';

		if (email.indexOf('@') == -1)
			return 'Электронный адрес должен содержать символ @';
			
		return '';
	}