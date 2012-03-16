<!-- REGISTER FORM COMPLETE -->
<div class="formContainer">
	<strong>Estás logado, podes alterar a tua informação pessoal.</strong>
	<div class="formContainer">
	<div class="formValidator">{error_message}</div>
	<form name="registerForm" id="registerForm" action="{REQUEST_URI}" method="post">
		<fieldset>
			<legend>Informação Pessoal</legend>
			<label for="first_name">Primeiro Nome: 
				<input type="text" name="first_name" id="first_name" value="{first_name}" size="32" tabindex="10" placeholder="O seu primeiro Nome" required/>
			</label>
			<label for="last_name">Apelido: 
				<input type="text" name="last_name" id="last_name" value="{last_name}" size="32" tabindex="10" placeholder="O seu ultimo Nome" required/>
			</label>
			<label for="nickname">Nickname(<small>Nome que os outros utilizadores irão ver.</small>): 
				<input type="text" name="nickname" id="nickname" value="{nickname}" size="20" tabindex="10" placeholder="Nome Que os outros utilizadores vão ver" required/>
			</label>
			<label for="user_email">Email: 
				<input type="text" name="user_email" id="user_email" value="{user_email}" size="32" tabindex="10" placeholder="O seu email" required/>
			</label>
			<label for="user_email">Morada: 
				<input type="text" name="adress" id="adress" value="{adress}" size="32" tabindex="10" placeholder="O seu email" required/>
			</label>
			<label for="user_email">Localidade: 
				<input type="text" name="localidade" id="localidade" value="{localidade}" size="32" tabindex="10" placeholder="O seu email" required/>
			</label>
			<label for="user_email">Código Postal: 
				<input type="text" name="codPostal" id="codPostal" value="{codPostal}" size="32" tabindex="10" placeholder="O seu email" required/>
			</label>
		</fieldset>
		<input type="submit" name="wp-submit-update" id="wp-submit-update" class="button-primary" value="Actualizar" tabindex="100" />
	</form>
</div>
</div>
<!-- END REGISTER FORM COMPLETE -->