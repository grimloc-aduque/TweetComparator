<?php
  
    include("./funciones_db.php");
    session_start();
    if (array_key_exists('auth', $_SESSION)){
      header('Location: ../index.php');        
    }
    else{
      if (array_key_exists('user',  $_POST) && array_key_exists('passwd', $_POST)) {
        $user = filter_var($_POST['user'], FILTER_SANITIZE_STRING); 
        $passwd = filter_var($_POST['passwd'], FILTER_SANITIZE_STRING); 
        $connection = conectarBdd();
        $registro = consultarUsuario($connection, $user, $passwd);
        if ($registro!=null){
          setcookie("user", $registro['user'], time() + 60000);		
          $_SESSION['auth'] = true;
          $_SESSION['user'] = $user;
          console_log($_SESSION["user"]);
          header('Location: ../index.php');
        }

      }
    }

?>





<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@200;500&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <title>
      Login & Sign up
    </title>
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <div class="main">
      <div class="form-c">
        <div class="boton-c">
          <div id="btn"></div>
          <button type="button" class="toggle-btn" onclick="login()"><p1>Log In</p1></button>
          <button type="button" class="toggle-btn" onclick="register()"><p1>Register</p1></button>
        </div>
        <form id="login" action="./login.php" method="post" class="input-g">
          <input type="text" class="input-field" name="user" placeholder="Username" required>
          <input type="text" class="input-field" name = "passwd" placeholder="Contraseña" required>
          <input type="checkbox" class="check-box"><span>Recordar Contraseña</span>
          <button type="submit" class="submit-btn">Iniciar Sesion</button>
        </form>
        <form id="register" action="./crearUsuario.php" method="post" class="input-g">
          <input type="text" class="input-field" name="user" placeholder="Username" required>
          <input type="email" class="input-field" name="mail" placeholder="Correo Electronico" required>
          <input type="text" class="input-field" name="password" placeholder="Contraseña" required>
          <input type="checkbox" class="check-box"><span>Acepto terminos y condiciones</span>
          <button type="submit" class="submit-btn">Registrarse</button>
        </form>
      </div>
    </div>

    <script type="text/javascript">

      var x = document.getElementById("login");
      var y = document.getElementById("register");
      var z = document.getElementById("btn");

      function register(){
        x.style.left = "-400px";
        y.style.left = "50px";
        z.style.left = "110px";
      }

      function login(){
        x.style.left = "50px";
        y.style.left = "450px";
        z.style.left = "0px";
      }

    </script>

  </body>
</html>