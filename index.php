<?php
    session_start();
    include("./pages/funciones_db.php");
    include("./pages/coneccionTweeter.php");

    if (array_key_exists('auth', $_SESSION) && $_SESSION['auth']==true ){

      if( array_key_exists("tipoBusqueda", $_GET) && array_key_exists("numTweets", $_GET) &&
          array_key_exists("busqueda1", $_GET) && array_key_exists("busqueda2", $_GET) ){
            $connection = conectarBdd();
            $user = $_SESSION["user"];
            $tipoBusqueda = filter_var($_GET['tipoBusqueda'], FILTER_SANITIZE_STRING); 
            $numTweets = filter_var($_GET['numTweets'], FILTER_SANITIZE_STRING); 
            $busqueda1 = filter_var($_GET['busqueda1'], FILTER_SANITIZE_STRING); 
            $busqueda2 = filter_var($_GET['busqueda2'], FILTER_SANITIZE_STRING); 
            //console_log($user . $tipoBusqueda .$numTweets .$busqueda1 .$busqueda2);

            $recordId = crearComparacion($connection, $user, $tipoBusqueda, $numTweets, $busqueda1, $busqueda2);  
            //console_log($recordId);
            header("Location: ./pages/comparacion.php?recordId=". $recordId . "");
      }


    }
    else{
      header("Location: ./pages/login.php");
    }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Busqueda</title>
      <link rel="stylesheet" href="./css/HMstyle.css">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@200;500&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="main">
      <section>
        <form id="searchBox" action="./index.php" class="input-g">
          <input type="text" class="input-field" name="busqueda1" placeholder="Hashtag 1" required>
          <br>
          <input type="text" class="input-field" name="busqueda2" placeholder="Hasthag 2" required>
          <br> <br>
          <button type="submit" class="submit-btn">Buscar</button>
        </form>
      </section>
    </div>
  </body>
</html>
