<?php
    session_start();
    include("./js/funciones_js.php");
    include("./apis/TwitterAPIExchange.php");
    include("./pages/funciones_db.php");
    
    include("./pages/contenido/historial.php");
    include("./pages/coneccionTweeter.php");


    if (array_key_exists('auth', $_SESSION) && $_SESSION['auth']==true ){
      $recordId = 1;
      $connection = conectarBdd();
      $user = $_SESSION["user"];
      

      if( array_key_exists("tipoBusqueda", $_GET) && array_key_exists("numTweets", $_GET) &&
          array_key_exists("busqueda1", $_GET) && array_key_exists("busqueda2", $_GET) ){
            
            
            $tipoBusqueda = filter_var($_GET['tipoBusqueda'], FILTER_SANITIZE_STRING); 
            $numTweets = filter_var($_GET['numTweets'], FILTER_SANITIZE_STRING); 
            $busqueda1 = filter_var($_GET['busqueda1'], FILTER_SANITIZE_STRING); 
            $busqueda2 = filter_var($_GET['busqueda2'], FILTER_SANITIZE_STRING); 
            //console_log($user . $tipoBusqueda .$numTweets .$busqueda1 .$busqueda2);

            $recordId = crearComparacion($connection, $user, $tipoBusqueda, $numTweets, $busqueda1, $busqueda2);  
            if($recordId != null){
              //console_log($recordId);
              header("Location: ./pages/contenido/comparacion.php?recordId=". $recordId . "");
            }            
      }


    }
    else{
      header("Location: ./pages/login/login.php");
    }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@200;500&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Busqueda</title>
      <link rel="stylesheet" href="./css/HMstyle.css">

  </head>
  <body>
    <div class="main">

      <div class="navbar">
        <a href="./index.php">Home</a>
            <div class="dropdown">
                <button class="dropbtn">Historial
                <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                  <?php
                    historial($connection, $user, 5, "./pages/contenido/");
                  ?>

                </div>
            </div>
          <a href="./pages/login/cerrar_sesion.php">Cerrar Sesión</a>
        </div>


      <section>
        <form id="searchBox" action="./index.php" class="input-g">
          <h2>Compare Tweets por menciones de Usuario o Hashtag</h2>
          <br>
          <div class="switch-field">
            <input type="radio" id="radio-four" name="tipoBusqueda" value="hashtag" checked/>
              <label for="radio-four">Hashtags</label>
            <input type="radio" id="radio-three" name="tipoBusqueda" value="screen_name" />
              <label for="radio-three">Usuarios</label>

          </div>
          <input type="text" class="input-field" name="busqueda1" placeholder="Busqueda 1" required>
          <br>
          <input type="text" class="input-field" name="busqueda2" placeholder="Busqueda 2" required>
          <br><br>
          <h2>Numero de resultados:</h2>             
          <div class="regular-field">
            <input type="radio" id="radio-six" name="numTweets" value="3" checked/>
              <label for="radio-six">3</label>
            <input type="radio" id="radio-seven" name="numTweets" value="6" />
              <label for="radio-seven">6</label>
            <input type="radio" id="radio-eight" name="numTweets" value="9" />
              <label for="radio-eight">9</label>
            <input type="radio" id="radio-eight" name="numTweets" value="12" />
              <label for="radio-eight">12</label>
          </div>
          <button type="submit" class="submit-btn">Buscar</button>
        </form>
      </section>
    </div>
    <?php
      if($recordId==null){
        alert("No se encontraron tweets de su búsqueda");
        recargarUrl("./index.php");
      }
    ?>

  </body>
</html>


