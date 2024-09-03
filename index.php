<?php
// You can get your API KEY from here: https://developers.google.com/speed/docs/insights/v5/get-started?hl=es-419
$GOOGLE_API_KEY = 'YOUR_API_KEY';
?>

<style>
    .btn {
        padding: 10px;
        border-radius: 10px;
        border: 1px solid black;
    }
    .btn-FAST {
        background-color: lightgreen;
    }
    .btn-SLOW {
        background-color: lightcoral;
    }
    .btn-AVERAGE {
        background-color: lightyellow;
    }
    .btn-NONE {
        background-color: lightgrey;
    }

    ul {
        column-count: 2;
        list-style: none;
    }
    li {
        min-height: 45px;
    }

    .score {
        font-weight: bold;
        font-size: 24px;
    }
    .score-list {
        list-style: none;
        padding: 20px;
        border: 2px solid black;
        border-radius: 10px;
    }


</style>
<?php
$websites = isset($_GET['url']) ? $_GET['url']:'https://www.google.es';
$websites = explode(',', $websites);

if ( $websites ) {
    $cnt = 1;
    foreach ( $websites as $url_destino ) {

        #$url_destino = isset($_GET['url']) ? $_GET['url']:'https://www.google.es';
        $strategy = isset($_GET['strategy']) ? $_GET['strategy']:'mobile';

        // llamada a la API de Google PageSpeed Insights
        $url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=' . $url_destino . '&strategy=' . $strategy . '&locale=es-ES&key=' . $GOOGLE_API_KEY;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $json = curl_exec($ch);
        curl_close($ch);


        $data = json_decode($json, true);

        // web url
        echo "<h1>Website: " . $url_destino . "</h1>";
        // Performance (score)
        $audits = $data['lighthouseResult']['categories'];
        if ( $audits ) {
            $button_class = '';
                switch ($audits['performance']['score']) {
                    case $audits['performance']['score'] < 0.5:
                        $button_class = 'btn-SLOW';
                        break;
                    case $audits['performance']['score'] < 0.75:
                        $button_class = 'btn-AVERAGE';
                        break;
                    case $audits['performance']['score'] >= 0.75:
                        $button_class = 'btn-FAST';
                        break;
                }
            echo '<ul class="score-list ' . $button_class . '">';
            foreach ( $audits as $key => $value ) {
                echo '<li class="score">' . $key . ': <span class="score-value">' . $value['score'] . '</span></li>';
            }
            echo "</ul>";
        }


        // core vitals
        if ( isset($data['loadingExperience']['metrics']) ) {
            $metrics = $data['loadingExperience']['metrics'];
            echo "<ul>";
            foreach ( $metrics as $key => $value ) {
                $button = '<span class="btn btn-' . $value['category'] . '">' . $value['category'] . '</span>';
            echo "<li>" . $button . ' (' . $value['percentile'] . ') ' . $key . '</li>';
            }
            echo "</ul>";
        } else {
            echo "<p>No hay datos de Core Vitals</p>";
        }

        echo '<hr>';

/*
        // Notificamos?
        if ( $data['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['category'] == 'SLOW' ) {
            echo "<p>Notificar a los desarrolladores</p>";
        }

        // Notificamos si la performance es menor a 0.5
        if ( $data['lighthouseResult']['categories']['performance']['score'] < 0.5 ) {
            echo "<p>Notificar a los desarrolladores</p>";
        }


        // guardamos en base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pagespeed";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Fallo de conexión: " . $conn->connect_error);
        }

        // core vitals
        if ( $metrics ) {
            foreach ( $metrics as $key=>$metric ) {
                $sql = "INSERT INTO pagespeed (url, strategy, name, value)
                VALUES ('" . $url_destino . "', '" . $strategy . "', '" . $key . "', '" . $metric['percentile'] . "')";
                if ($conn->query($sql) === TRUE) {
                    echo "Datos guardados correctamente";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        // performance
        $audits = $data['lighthouseResult']['categories'];
        if ( $audits ) {
            foreach ( $audits as $key => $value ) {
                $sql = "INSERT INTO pagespeed (url, strategy, name, value)
                VALUES ('" . $url_destino . "', '" . $strategy . "','" . $key . "', '" . $value . "')";
                if ($conn->query($sql) === TRUE) {
                    echo "Datos guardados correctamente";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            
            }
        }
*/
        $cnt++;
    }
}