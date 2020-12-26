<?php
include("../funciones_db.php");


session_start();
if (array_key_exists('user',  $_POST) && array_key_exists('mail', $_POST) && array_key_exists('password', $_POST) ){
    
    $connection = conectarBdd();
    $user = filter_var($_POST['user'], FILTER_SANITIZE_STRING); 
    $mail = filter_var($_POST['mail'], FILTER_SANITIZE_STRING); 
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING); 
    
    insertUser($connection, $user, $mail, sha1($password));
    
    if(consultarUsuario($connection, $user, $password)!=null){
        //setcookie("user", $user, time() + 60000);		
        $_SESSION['auth'] = true;
        $_SESSION["user"] = $user;
        header("Location: ../../index.php");
        
    }
}

header("Location: ./login.php");

  


?>