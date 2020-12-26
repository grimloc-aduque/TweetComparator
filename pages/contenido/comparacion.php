<?php
    session_start();

    include("../funciones_db.php");
    include("../../js/funciones_js.php");
    include('..//../apis/QuickChart.php');
    include("./historial.php");
    include("./funcionesComparacion.php");


    if (array_key_exists('auth', $_SESSION) && $_SESSION['auth']==true ){
        if(array_key_exists("recordId", $_GET) ){
            $user = $_SESSION["user"];
            $recordId = filter_var($_GET['recordId'], FILTER_SANITIZE_STRING);
            //$recordId = $_GET["recordId"];
            $connection = conectarBdd();
            $record = getRecordById($connection, $recordId);
            $busqueda1 = $record["busqueda1"];
            $busqueda2 = $record["busqueda2"];
        }else{
            header("Location: ../index.php");
        }

    }else{
        header("Location: ../login/login.php");
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@200;500&display=swap" rel="stylesheet">
        <meta charset="UTF-8">
        <title>Comparacion</title>
        <link rel="stylesheet" href="../../css/resultados.css">

    </head>
    <body>
        <div class="main">
        <!-- <div> -->
            <div class="navbar">
                <a href="../../index.php">Home</a>
                    <div class="dropdown">
                        <button class="dropbtn">Historial
                        <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                        <?php
                            historial($connection, $user, 5, "./");
                        ?>
                        </div>
                    </div>
                <a href="../login/cerrar_sesion.php">Cerrar Sesión</a>
            </div>

            <div class="row">
                <div class="column" >
                    <div class="r1">
                    <?php
                        echo ("<h2>" . $busqueda1 .  "</h2>");
                        ultimosTweets($connection, $recordId, $busqueda1);
                    ?>
                    </div>
                </div>

                <div class="column" >
                    <div class="r2">
                    <?php
                        echo ("<h2>" . $busqueda2 .  "</h2>");
                        ultimosTweets($connection, $recordId, $busqueda2);
                    ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="column" >
                    <div class="r1">
                    <?php
                        frecuenciaPalabras($connection, $recordId, $busqueda1, 6);
                        echo ("<br><br><br><br>");
                        popularidadBarras($connection, $recordId, $busqueda1);
                        echo ("<br><br><br><br>");
                        popularidadTimeLine($connection, $recordId, $busqueda1);
                        echo ("<br><br><br><br>");
                    ?>
                    </div>
                </div>

                <div class="column" >
                    <div class="r2">
                    <?php
                        frecuenciaPalabras($connection, $recordId, $busqueda2, 6);
                        echo ("<br><br><br><br>");
                        popularidadBarras($connection, $recordId, $busqueda2);
                        echo ("<br><br><br><br>");
                        popularidadTimeLine($connection, $recordId, $busqueda2);
                        echo ("<br><br><br><br>");
                    ?>
                    </div>
                </div>
            </div>




            <div class="row">
                <div class="column2">
                    <div class="r1">
                        <?php
                        echo ("<h2>" . $busqueda1 ." vs " . $busqueda2 .  "</h2>");
                        comparacionBarras($connection, $recordId, $busqueda1, $busqueda2);
                        echo ("<br><br>");
                        ?>
                    </div>
                </div>
            </div>
            <br>


        </div>
    </body>
</html>

