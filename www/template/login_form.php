	<style type="text/css">
		#login_form{
			padding:20px;
		}
		
		#login_form table{
			padding: 20px 60px;
			margin:0 auto;
			border-radius: 12px;
			font-weight: bold;
			color: #fff;
			background-color: #7BA800;
		}
		
		#login_form table td{
			padding:5px;
		}
	</style>

	<form action="/auth" method="POST" id="login_form">	
		<table>
			<tr>
				<td>Логин</td>
				<td><input type="text" name="login_input" style="width:150px;" autofocus autocomplete="off"> </td>	
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="password" onclick="this.select()" name="password_input" style="width:150px;"> </td>	
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="login_button" value="Войти" style="width:220px; height:30px; margin-top:10px; cursor:pointer;">
				</td>
			</tr>		
		</table>
	</form>