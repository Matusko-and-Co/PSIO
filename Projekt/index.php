<?php
	//nastartovanie session v php
	session_start();
	
	date_default_timezone_set("Europe/Bratislava");
	
	//rychla kontrola, aby sa predislo hijacku
	if(isset($_SESSION['PREV_USER_AGENT']) && $_SESSION['PREV_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']){
		session_destroy();
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 5000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		session_start();
		
		session_regenerate_id();
	}
	$_SESSION['PREV_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Predikcia šírenie infekčných ochorení</title>
		
		<link rel="stylesheet" href="css/mainStyle.css" />

		<script src="js/jquery/jquery-2.1.4.min.js"></script>
		<script src="js/jquery/jquery-ui.min.js"></script>
		<script src="js/jquery/jquery.validate.min.js"></script>

		<script src="js/sefovica.js"></script>
		<script src="js/GetDataFromServer.js"></script>
	</head>
	<body>
<?php
				//pridavanie stranky
				if(!isset($_GET['page'])){
					include('./main.php');
				}else if($_GET['page'] == 'sefovica'){
					include('./sefovica.php');
				}
?>
		<footer>
			<p>
				Copyright &copy; 2015 <br />
			</p>
		</footer>
	</body>
</html>