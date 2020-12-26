<?php



    // ---------------------------------TWEETS----------------------------------------

    function ultimosTweetsKeyValue($connection, $recordId, $busqueda){
        echo ("<h3>Ultimos tweets</h3>");
        $tweets = getTweets($connection, $recordId,  $busqueda);
        $i = 0;
        foreach($tweets as $tweet){
          $i ++ ;
          echo ("<div class=\"tweet\">");
            foreach($tweet as $clave=>$valor){
              if($clave != "link")
                echo ("<b>" .  ucfirst($clave) . "</b>"  . " => " . $valor . "<br>");
              else
                echo ("<b>" .  ucfirst($clave) . "</b>" . " => <a style=\"color: #E15545;\"  href=\"" . $valor . " \"> " .  $valor ."</a><br>");
            }
          
          echo ("</div>");
          if($i<sizeof($tweets))

            echo("<br><br>");
        }
    }



    function ultimosTweets($connection, $recordId, $busqueda){
      echo ("<h3>Ultimos tweets</h3>");
      $tweets = getTweets($connection, $recordId,  $busqueda);
      $i = 0;
      foreach($tweets as $tweet){
        $i ++ ;
        echo ("<div class=\"tweet\">");
        $screen_name = $tweet["screen_name"];
        $s = "<h4> <span style='color: #E15545;'>@</span> ". $screen_name   ."</h4>";

        if( $tweet["hashtags"] != null){
          $hashtags = explode(" , ", $tweet["hashtags"]);
          $s = $s . "<span>";
          foreach($hashtags as $hashtag)
            $s = $s . "<span style='color: #E15545;'>#</span>" . $hashtag . "\t\t";
          $s = $s . "</span>";
  
        }

        if( $tweet["user_mentions"]!=null){
          $user_mentions = explode(" , ", $tweet["user_mentions"]);
          $s = $s . "<span>";
          foreach($user_mentions as $mention)
            $s = $s . "<span style='color: #E15545;'>@</span>". $mention . "\t\t";
          $s = $s . "</span>";
        }

        $text = $tweet["text"];
        $s = $s . "<p>  $text <p>";

        $link = $tweet["link"];
        $s = $s . "<a style='color: #E15545; text-decoration:none'  href='" . $link . " '> " .  $link ."</a>";


        echo ($s);
                
        echo ("</div>");
        if($i<sizeof($tweets))
          echo("<br><br>");
      }
  }


    // ---------------------------------FRECUENCIAS----------------------------------------

    function tokenizar($tweets){
        $text = "";
        foreach($tweets as $tweet){
            //console_log($tweet);
            $text = $text . $tweet["text"];
        }
        $text= str_replace(array(",",".", ";","\\", "\"","'", "-","@", "&", "#", ":", "/", "https", "://t.co/", "amp"), " ", $text);
        $text = preg_replace("/\s+/", " ", $text);
        $text = strtolower($text);
        $palabras = explode(" ", $text, str_word_count($text));
        //console_log($palabras);
        $contadorPalabras = array();

        foreach ($palabras as $palabra) {
            if(array_key_exists($palabra, $contadorPalabras)){
              $contadorPalabras[$palabra] +=1 ;
            }else{
              $contadorPalabras[$palabra] = 1;
            }
          }
        arsort($contadorPalabras);
        return $contadorPalabras;
    }



    function frecuenciaPalabras($connection, $recordId, $busqueda, $max){
        echo ("<h3>Frecuencia de palabras</h3>");
        $tweets = getTweets($connection, $recordId,  $busqueda);        
        $contadorPalabras = tokenizar($tweets);
        echo ("
        <table style=\"   margin-left: auto;      margin-right: auto;\">
          <tr >
            <th>Palabras</th>
            <th>Frecuencias</th>
          </tr>
        ");
          
          $i = 0;
          $size = 18;
          //$color = 6°, 72%, 58%;

          foreach($contadorPalabras as $palabra => $frecuencia){
            if($i==$max)
                break;
            if(strlen($palabra)<=2)
                continue;            
            
            $sizeTemp = $size + (5-$i)*(5-$i);
            $s = 72-($i)*($i)*0.8;

            if($i%2==0){
              $fila = "<tr>
              <td style = \" font-size: ". $sizeTemp . "px; color: hsl(6,".$s ."% ,58%) ; border: 3px; \">" . $palabra . "</td>
              <td style = \" font-size: ". $sizeTemp . "px;\">" . $frecuencia . "</td>
              </tr>";
            }else{
              $fila = "<tr>
              <td style = \" font-size: ". $sizeTemp . "px; border: 3px; \">" . $palabra . "</td>
              <td style = \" font-size: ". $sizeTemp . "px;\">" . $frecuencia . "</td>
              </tr>";
            }
 

            $i+=1;
            //console_log($fila);
            echo($fila);
            
          }
          echo ("</table>");
    }






    // ---------------------------------GRAFICAS----------------------------------------



    function popularidadBarras($connection,  $recordId, $busqueda){
        echo ("<h3>Barras de popularidad</h3>");
        $avgRetweet = getAvgRetweet($connection, $recordId, $busqueda);
        $avgFavorite = getAvgFavorite($connection, $recordId, $busqueda);
        
        $qc = new QuickChart();
        $qc->setConfig("{
            type: 'bar',
            data: {
                labels: ['Favorite Avg', 'Retweet Avg'],
                datasets: [{
                    backgroundColor: '#E15545' , 
                    label: '" . $busqueda . "',
                    data: [". $avgFavorite .", ". $avgRetweet . "]
                }]
            }
        }");
        //echo ($qc->getUrl())
      ?>
        <img src="<?php echo ($qc->getUrl())  ?>" > 
      <?php
    }



    function popularidadTimeline($connection, $recordId, $busqueda){
        echo ("<h3>Timeline de Popularidad</h3>");
        $data = getTimelineData($connection, $recordId, $busqueda);
        $labels = [];
        $dataFav = [];
        $dataRet = [];

        foreach($data as $tweet){
            $labels[] = $tweet["date"];
            $dataFav[] = $tweet["favorite_count"];
            $dataRet[] = $tweet["retweet_count"];
        }

        $labelsS = "";
        $dataFavS = "";
        $dataRetS = "";
        for($i=0; $i<sizeof($labels); $i++) {
            $labelsS = $labelsS .  "'" . $labels[$i] . "'";
            $dataFavS = $dataFavS .  "'" . $dataFav[$i] . "'";
            $dataRetS = $dataRetS .  "'" . $dataRet[$i] . "'";

            if($i!=sizeof($labels)-1){
                $labelsS = $labelsS . ", ";
                $dataFavS = $dataFavS .  ", ";
                $dataRetS = $dataRetS .  ", ";
            }
        }

        $qc = new QuickChart();
        $qc->setConfig("{
            type: 'line',
            data: {
                labels: [" . $labelsS .   "],
                datasets: [
                {
                    label: 'Retweet',
                    data: [". $dataRetS. "],
                    borderColor: '#E15545',
                    fill: false
                },
                {
                    label: 'Favorite',
                    data: [". $dataFavS. "],
                    borderColor: 'black',
                    fill: false
                }
                ]
            }
        }");
        //echo ($qc->getUrl())
      ?>
        <img src="<?php echo ($qc->getUrl())  ?>" > 

      <?php
    }



    function comparacionBarras($connection, $recordId, $busqueda1, $busqueda2){
        echo ("<h3>Comparacion Popularidad</h3>");
        $avgRetweet1 = getAvgRetweet($connection, $recordId, $busqueda1);
        $avgFavorite1 = getAvgFavorite($connection, $recordId, $busqueda1);
        $avgRetweet2 = getAvgRetweet($connection, $recordId, $busqueda2);
        $avgFavorite2 = getAvgFavorite($connection, $recordId, $busqueda2);
        

        $qc = new QuickChart();
        $qc->setConfig("{
            type: 'bar',
            data: {
                labels: ['Favorite Avg', 'Retweet Avg'],
                datasets: [{
                    backgroundColor: '#E15545', 
                    label: '" . $busqueda1 . "',
                    data: [". $avgFavorite1 .", ". $avgRetweet1 . "]
                },{
                    backgroundColor: 'black', 
                    label: '" . $busqueda2 . "',
                    data: [". $avgFavorite2 .", ". $avgRetweet2 . "]
                }]
            }
        }");
        //echo ($qc->getUrl())
      ?>
        <img style=" display: block;;  margin-left: auto;  margin-right: auto;" src="<?php echo ($qc->getUrl())  ?>" > 

      <?php
    }




    

?>
