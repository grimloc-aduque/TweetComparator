<?php

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }

    function alert($data){
        echo '<script>';
        echo "alert(\"". $data ."\")";
        echo '</script>';
    }    

    function recargarUrl($url){
        echo '<script>';
        echo ( "window.history.pushState( {} , document.title, \"". $url ."\")" );
        echo '</script>';
        
    }

    function redireccionar($url){
        //console_log($GLOBALS['redireccion']);
        if( array_key_exists("redireccion", $GLOBALS) && $GLOBALS["redireccion"]==true ){
            header("Location: ./" . $url);
        }
    }



?>

