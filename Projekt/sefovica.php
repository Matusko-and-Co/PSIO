<header>
	<h1>Administrácia</h1>
</header>
<main>


<?php
	require('./php/Passwd.php');
	
	// Ak je zadane heslo, skontroloujem ho, ak je dobre, vytvorim seesion, aby sa to pametalo a zobrazi sa administracia.
	if(isset($_POST['passwordSubmit'])){
		$passwordChecker = new Passwd();
		if($passwordChecker->comparePasswords($_POST['password'])){
			$_SESSION['allowedAdministration'] = 1;
		}
	}
	// Ak som prihlaseny, ak odoslem formular na zmenu hesla, zmeni sa heslo, inak sa uploadne subor.
	if(isset($_SESSION['allowedAdministration']) && $_SESSION['allowedAdministration'] == 1):
		require('./php/Administration.php');
		
		$admin = new Administration();
		if(isset($_POST['changePassword'])){
			$admin->changePassword($_POST['oldPassword'], $_POST['newPassword'], $_POST['newPasswordCheck']);
		}else if(isset($_POST['submitData'])){
			$admin->uploadFile($_FILES['newData']);
		}
?>
				<p class="skript" id="fileHint">
				- súbor musí byť vo formáte .xls alebo .xlsx<br />
				- súbot musí mať maximálne 1 MB
				</p>

				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=sefovica" enctype="multipart/form-data" id="fileForm">
					<input type="file" name="newData" /><br>
					<input type="submit" name="submitData" id="submitData" value="Nahrať dáta" />
				</form>

				<div id="changePass">
					<h2>Zmena hesla</h2>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=sefovica" id="passwordInputs">
						<label for="oldPassword">Zadajte staré heslo: </label><input type="password" name="oldPassword" id="oldPassword" /><br>
						<label for="newPassword">Zadajte nové heslo: </label><input type="password" name="newPassword" id="newPassword" /><br>
						<label for="newPasswordCheck">Zadajte nové heslo ešte raz: </label><input type="password" name="newPasswordCheck" id="newPasswordCheck" /><br>
						<input type="submit" name="changePassword" id="changePassword" value="Zmeniť heslo" />
					</form>
				</div>
<?php
	else:
?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=sefovica" enctype="multipart/form-data">
					<p class="skript">
						<label for="password">Zadajte heslo: </label>
						<input type="password" name="password" id="password" /><br>
						<input type="submit" name="passwordSubmit" value="Potvrdiť" />
					</p>
				</form>
<?php
endif;
?>


</main>