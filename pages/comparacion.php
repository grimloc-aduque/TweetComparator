

﻿<?php

    session_start();
    include("funciones_db.php");
    include("funcionesComparacion.php");


    if (array_key_exists('auth', $_SESSION) && $_SESSION['auth']==true ){

        if(array_key_exists("recordId", $_GET) ){
            $user = $_COOKIE["user"];
            //$recordId = filter_var($_GET['recordId'], FILTER_SANITIZE_STRING); 
            $recordId = $_GET["recordId"];
            $connection = conectarBdd();
            $record = getRecordById($connection, $recordId);
            $tipoBusqueda = $record["tipo_busqueda"];
            $busqueda1 = $record["busqueda1"];
            $busqueda2 = $record["busqueda2"];

        }else{
            header("Location: ../index.php");
        }

    }else{
        header("Location: ./login.php");
    }
        
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparacion</title>
</head>
<body>
    <h1>Comparacion</h1>



    <div class="container">
        <div class="row">
            <div class="col-sm">
            <?php
            
                echo ("<h2>" . $busqueda1 .  "</h2>");
                ultimosTweets($connection, $tipoBusqueda, $recordId, $busqueda1);
                echo ("<br><br>");
                nubePalabras($connection, $tipoBusqueda, $recordId, $busqueda1);
                echo ("<br><br>");
                popularidadBarras($connection, $tipoBusqueda, $recordId, $busqueda1);
                echo ("<br><br>");
                popularidadTimeLine($connection, $tipoBusqueda, $recordId, $busqueda1);
                
            ?>
            </div>
            <div class="col-sm">
            <?php
                echo ("<h2>" . $busqueda2 .  "</h2>");
                ultimosTweets($connection, $tipoBusqueda, $recordId, $busqueda2);
                echo ("<br><br>");
                nubePalabras($connection, $tipoBusqueda, $recordId, $busqueda2);
                echo ("<br><br>");
                popularidadBarras($connection, $tipoBusqueda, $recordId, $busqueda2);
                echo ("<br><br>");
                popularidadTimeLine($connection, $tipoBusqueda, $recordId, $busqueda2);
            ?>
            </div>
        </div>
    </div>

    <br><br><br><br>

    <div>
        <?php
            echo ("<h2>" . $busqueda1 ." vs " . $busqueda2 .  "</h2>");
            comparacionBarras($connection, $tipoBusqueda, $recordId, $busqueda1, $busqueda2);
            echo ("<br><br><br>");
            
        ?>
    </div>

    



    <div>
        <?php
            echo ("<h2> Historial </h2>");
            historial($connection, $user, 5);
            
        ?>
    </div>
    
</body>
</html>