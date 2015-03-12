<?php
    $lat=$_REQUEST['lat'];
    $lon=$_REQUEST['lon'];
    $base="http://api.weather.com/v2/geocode/$lat/$lon/aggregate.json?";
        $params = array(
            'apiKey'=>'###insert your key here###',
            'units'=>'e',
            'language'=>'en',
            'products'=> 'conditions'
        );
        $secret="###if using a secret, put it here";
    $uri=http_build_query($params);
    $url="$base$uri";
    if ($secret)
    {
        $date = strftime("%a, %d %b %Y %H:%M:%S GMT");
        $err= preg_match('^http(s|)://[a-z0-9.]+(/[^?]+)?',$url, $match);
        $hash=hash('sha256',$match[2]);
        $authorization = "TWC $hash";
        echo "hash - $hash -
";

        $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Accept-language: en\r\n" .
                    "Date: $date\r\n" .
                    "Authorization: $authorization\r\n"
                    )
                );
        $context = stream_context_create($opts);
    }
    $json=json_decode(file_get_contents("$url",false,$context));
    $temp = $json->conditions->data->observation->imperial->temp;
    $cond =$json->conditions->data->observation->phrase_32char;
    $icon =$json->conditions->data->observation->icon_code;
?>

<html lang="en">
  <head>
    <link type="text/css" rel="stylesheet" href="css/hero.css" />
    <meta charset="UTF-8">
  </head>
  <body>
  <div id="weather">
      <ul id="icon">
        <li id='icon_'><img src="http://icons.wunderground.com/graphics/autobrand/sfgate2012/current_icons/<?$icon?>.png" /></li>
      </ul>
      <ul class="delete">
        <li><?echo $temp?></li>
        <li><?echo $cond?></li>
        <li><?echo $cond?></li>
      </ul>
    </div>
  </body>
</html>
