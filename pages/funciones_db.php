<?php

    // CONEXION
    function conectarBdd() {
        $dbsystem='mysql';
        $host='localhost';
        $dbname='proyecto_twitter';
        $username='root';
        $passwd='';            
        $connection = mysqli_connect($host, $username, $passwd, $dbname);
        return $connection;
    }



    // USUARIO
    function consultarUsuario($connection, $usuario, $password) {
        $query = "SELECT * FROM user where user = '$usuario' and password = sha1('$password')";
        $result = mysqli_query($connection, $query);
        
        $resultFetched = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //console_log($resultFetched);
        if(sizeof($resultFetched)!=0){
            return $resultFetched[0];
        }
        
        return null;
    }


    function insertUser($connection, $user, $mail, $password) {
        $query = "INSERT INTO user VALUES (?, ?, ?)";
        if($insert = mysqli_prepare($connection, $query)){
            mysqli_stmt_bind_param($insert, 'sss', $user, $mail, $password);
            mysqli_stmt_execute($insert);
        } 
        mysqli_stmt_close($insert);
    }


    // Record
    function insertRecord($connection, $user, $tipoBusqueda, $busqueda1, $busqueda2){
        $query = "INSERT INTO record (user, tipo_busqueda, busqueda1, busqueda2) VALUES (?, ?, ?, ?);";
        if($insert = mysqli_prepare($connection, $query)){
            mysqli_stmt_bind_param($insert, 'ssss', $user, $tipoBusqueda, $busqueda1, $busqueda2);
            $result = mysqli_stmt_execute($insert);
            mysqli_stmt_close($insert);
        }
    }


    function getLastRecordId($connection){
        $query = "SELECT record_id FROM record ORDER BY record_id DESC LIMIT 1";
        $result = mysqli_query($connection, $query);
        $id =  mysqli_fetch_all ($result)[0][0];
        return $id;
    }


    function getRecordById($connection, $recordId){
        $query = "SELECT * FROM record WHERE record_id='$recordId'";
        //console_log($query);
        $result = mysqli_query($connection, $query);
        return  mysqli_fetch_all ($result, MYSQLI_ASSOC)[0];
    }

    function getRecords($connection, $user, $num){
        $query = "SELECT * FROM record where user = '$user' ORDER BY record_id DESC LIMIT $num ";
        //console_log($query);
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }



    // TWEET
    function insertTweet($connection, $id, $screen_name, $date, $text, $favorite_count, $retweet_count, $hashtags, $user_mentions){
        $query = "INSERT INTO tweet (tweet_id, screen_name, date, text, favorite_count, retweet_count, hashtags, user_mentions)  
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if($insert = mysqli_prepare($connection, $query)){
            mysqli_stmt_bind_param($insert, 'isssiiss', $id, $screen_name, $date, $text, $favorite_count, $retweet_count, $hashtags, $user_mentions);
            mysqli_stmt_execute($insert);
        } 
        mysqli_stmt_close($insert);
    }


    function getQuery($select, $tipoBusqueda, $recordId, $busqueda){
        if($tipoBusqueda == "hashtag"){
            $query = "SELECT " . $select. " 
                        FROM tweet, record_tweet 
                        WHERE record_tweet.record_id = " . $recordId ." AND tweet.tweet_id = record_tweet.tweet_id  
                            AND tweet.hashtags LIKE \"%".$busqueda."%\" 
                            ORDER BY tweet.date DESC;";
            
        }else if($tipoBusqueda == "screen_name"){

            $query = "SELECT " . $select . "  
                        FROM tweet, record_tweet 
                        WHERE (record_tweet.record_id = " . $recordId ." AND tweet.tweet_id = record_tweet.tweet_id  
                            AND tweet.user_mentions LIKE \"%".$busqueda."%\" )
                            ORDER BY tweet.date DESC;" ;
        }
        return $query;

    }


    function getTweets($connection, $tipoBusqueda, $recordId, $busqueda){
        $select = "tweet.tweet_id, tweet.screen_name, tweet.date, tweet.text, tweet.favorite_count, tweet.retweet_count, tweet.hashtags, tweet.user_mentions";
        $query = getQuery($select, $tipoBusqueda, $recordId, $busqueda);
        //console_log($query);
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_all ($result, MYSQLI_ASSOC);
    }



    // Record_Tweet
    function insertRecordTweet($connection, $record_id, $tweet_id){
        $query = "INSERT INTO record_tweet VALUES (?, ?)";
        if($insert = mysqli_prepare($connection, $query)){
            mysqli_stmt_bind_param($insert, 'ii', $record_id, $tweet_id);
            mysqli_stmt_execute($insert);
        } 
        mysqli_stmt_close($insert);
    }



    // Data Gráficas
    function getTimelineData($connection, $tipoBusqueda, $recordId, $busqueda){
        $select = "tweet.date, tweet.retweet_count, tweet.favorite_count";
        $query = getQuery($select, $tipoBusqueda, $recordId, $busqueda);
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_all ($result, MYSQLI_ASSOC);
    }


    
    function getAvgRetweet($connection, $tipoBusqueda, $recordId, $busqueda){
        $select = "AVG(tweet.retweet_count)";
        $query = getQuery($select, $tipoBusqueda, $recordId, $busqueda);
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_all ($result)[0][0];
    }



    function getAvgFavorite($connection, $tipoBusqueda, $recordId, $busqueda){
        $select = "AVG(tweet.favorite_count)";
        $query = getQuery($select, $tipoBusqueda, $recordId, $busqueda);
        $result = mysqli_query($connection, $query);
        return mysqli_fetch_all ($result)[0][0]; 
    }




    // OTROS

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }

    function redireccionar($url){
        //console_log($GLOBALS['redireccion']);
        if( array_key_exists("redireccion", $GLOBALS) && $GLOBALS["redireccion"]==true ){
            header("Location: ./" . $url);
        }
    }

?>