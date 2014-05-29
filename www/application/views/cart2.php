
<div id="cart">

	<h2 id="cart_title" style="margin-left:50px;">Оформление заказа</h2>

	<form id='cart2_form' method='POST' action='/mail'>
		<table id='cart2_table'>			
			<tr>
				<td> Имя </td>
				<td> <input type='text' name='cart2_name'> </td>
				<td class='cart2_error'> </td>
			</tr>
			<tr>
				<td> Адрес </td>
				<td> <input type='text' name='cart2_address'> </td>
				<td class='cart2_error'> </td>
			</tr>
			<tr>
				<td> Телефон </td>
				<td> <input type='text' name='cart2_phone'> </td>
				<td class='cart2_error'> </td>
			</tr>
			<tr>
				<td> e-mail </td>
				<td> <input type='text' name='cart2_email'> </td>
				<td class='cart2_error'> </td>
			</tr>
		</table>
	
		<button class="cart_button" onClick="location.href='/cart'; return false;" style='width: 200px; margin-left:50px'>	Вернуться к корзине </button>
		<button class="cart_selected" style='width: 210px'> Подтверждение заказа </button>
	</form>
</div>


<script src='/js/checking_cart_data.js'> </script>
<script> initChecking(); </script>