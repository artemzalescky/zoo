
<? if ($success) { ?>

		<h2 style='margin-top:30px; text-align:center; font: bold 20px Arial; font-style: italic; color: #ff8200;'> Ваш заказ принят </h2>
		<h2 style='text-align:center; font: bold 16px Arial; font-style: italic; color: #678C01;'> В ближайшее время с Вами свяжутся наши продавцы </h2>

<?	}
	else { ?>
		<h2 style='margin-top:30px; text-align:center; font: bold 20px Arial; font-style: italic; color: #ff8200;'> Ошибка при отправке заказа! </h2>
		<h2 style='text-align:center; font: bold 16px Arial; font-style: italic; color: #678C01;'> Пожалуйста, повторите заказ позже или свяжитесь с продавцами <a style='color: #678C01' href='/contacts'> по телефону </a> </h2>
		<h2 style='text-align:center; font: bold 16px Arial; font-style: italic; color: #678C01;'> Приносим свои извинения за временные неудобства </h2>
<?	} ?>