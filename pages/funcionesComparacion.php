<?php


    include('../apis/QuickChart.php');


    function ultimosTweets($connection, $tipoBusqueda, $recordId, $busqueda){
        echo ("<h4>Ultimos tweets ". $busqueda ."</h4>");
        $tweets = getTweets($connection, $tipoBusqueda, $recordId,  $busqueda);
        foreach($tweets as $tweet){
            foreach($tweet as $clave=>$valor){
                echo ("<p>" . $clave  . " => " . $valor . "<p>");
            }
            echo("<br><br>");
        }
    }


    function nubePalabras($connection, $tipoBusqueda, $recordId, $busqueda){
        echo ("<h4>Nube palabras ". $busqueda ."</h4>");
        $lblFav = "Favorite Avg";
        $label2 = "Retweet Avg";
        $tweets = getTweets($connection, $tipoBusqueda, $recordId,  $busqueda);
        $text = "";
        foreach($tweets as $tweet){
            $text = $text . $tweet["text"];
        }
        $text= str_replace(array(",",".", ";","\\", "\"","\'", "-","   ", "  ", " ",":", "/", "http"), " ", $text);

        $url = "https://quickchart.io/wordcloud?text='". $text ."'";
        //console_log($url);
      ?>
        <img src="<?php echo $url  ?>" > 
      <?php
    }



    function popularidadBarras($connection, $tipoBusqueda, $recordId, $busqueda){
        echo ("<h4>Grafica Barras popularidad ". $busqueda ."</h4>");
        $avgRetweet = getAvgRetweet($connection, $tipoBusqueda, $recordId, $busqueda);
        $avgFavorite = getAvgFavorite($connection, $tipoBusqueda, $recordId, $busqueda);
        
        $qc = new QuickChart();
        $qc->setConfig("{
            type: 'bar',
            data: {
                labels: ['Favorite Avg', 'Retweet Avg'],
                datasets: [{
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



    function popularidadTimeline($connection, $tipoBusqueda, $recordId, $busqueda){
        echo ("<h4>Grafica Timeline popularidad ". $busqueda ."</h4>");
        $data = getTimelineData($connection, $tipoBusqueda, $recordId, $busqueda);
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
                    borderColor: 'rgb(255, 99, 132)',
                    fill: false
                },
                {
                    label: 'Favorite',
                    data: [". $dataFavS. "],
                    borderColor: 'rgb(54, 162, 235)',
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



    function comparacionBarras($connection, $tipoBusqueda, $recordId, $busqueda1, $busqueda2){
        echo ("<h4>Grafica Barras Comparacion Popularidad</h4>");
        $avgRetweet1 = getAvgRetweet($connection, $tipoBusqueda, $recordId, $busqueda1);
        $avgFavorite1 = getAvgFavorite($connection, $tipoBusqueda, $recordId, $busqueda1);
        $avgRetweet2 = getAvgRetweet($connection, $tipoBusqueda, $recordId, $busqueda2);
        $avgFavorite2 = getAvgFavorite($connection, $tipoBusqueda, $recordId, $busqueda2);
        

        $qc = new QuickChart();
        $qc->setConfig("{
            type: 'bar',
            data: {
                labels: ['Favorite Avg', 'Retweet Avg'],
                datasets: [{
                label: '" . $busqueda1 . "',
                data: [". $avgFavorite1 .", ". $avgRetweet1 . "]
                },{
                label: '" . $busqueda2 . "',
                data: [". $avgFavorite2 .", ". $avgRetweet2 . "]
                }]
            }
        }");
        //echo ($qc->getUrl())
      ?>
        <img src="<?php echo ($qc->getUrl())  ?>" > 

      <?php
    }


    function historial($connection, $user, $numTweets){
        $records = getRecords($connection, $user, $numTweets);
        foreach($records as $record){
            print_r ($record);
            $id = $record["record_id"];
            echo ("<a href=\"./comparacion.php?recordId=". $id ."\"> Ver record con id: " . $id . "</a>");
            echo ("<br><br>");
        }

    }


    

?>
