<?php
	include("funciones_db.php");
	session_start();

	if (array_key_exists('auth', $_SESSION) && $_SESSION['auth']==true){ 
		$_SESSION = array();
		session_destroy();
		console_log("Sesion cerrada");
		header("Location: ./login.php");

	}
	else{ 
		header("Location: ./login.php");
		exit;
	}

?>