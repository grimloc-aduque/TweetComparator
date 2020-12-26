<?php

function historial($connection, $user, $numTweets, $link){
    $records = getRecords($connection, $user, $numTweets);
    foreach($records as $record){
        //print_r ($record);
        $date = $record["datetime"];
        $tipo_busqueda = $record["tipo_busqueda"];
        $busqueda1 = $record["busqueda1"];
        $busqueda2 = $record["busqueda2"];
        $id = $record["record_id"];
        echo ("<a href=\"" . $link .  "comparacion.php?recordId=". $id ."\"> "  .
            "<b>"  . $busqueda1 . " vs " . "$busqueda2" . "</b>" .
            " <p style=\"font-size: 13px;\"> Fecha: " . $date . "</p>" . 
            " <p style=\"font-size: 13px;\"> Comparacion: " . $tipo_busqueda . "</p>" .

            "</a>");
    }

}



?>