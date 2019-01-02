<?php

function appel($url){
   // $opts = array('http' => array('proxy' => 'tcp://www-cache.iutnc.univ-lorraine.fr:3128/', 'request_fulluri' => true));

   // $context = stream_context_create($opts);

   // $str = file_get_contents($url, false, $context);
   $str = file_get_contents($url);
   if(http_response_code() === 200){
       return $str;
   } else {
       echo "Errors";
       return null;
   }
}

$ip = appel('http://ip-api.com/xml/');

$xml_ip = simplexml_load_string($ip);

$lat = $xml_ip->lat->__toString();
$lon = $xml_ip->lon->__toString();

$meteo = appel("http://www.infoclimat.fr/public-api/gfs/xml?_ll=" . $lat . "," . $lon . "&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2");

$xmlDoc = new DOMDocument();
$xmlDoc->loadXML($meteo);

$xslDoc = new DOMDocument();
$xslDoc->load("meteo.xsl");

$proc = new XSLTProcessor();
$proc->importStylesheet($xslDoc);

$velos = appel("https://api.jcdecaux.com/vls/v1/stations?contract=nancy&apiKey=6472d9b3102060921c1a408463b8bfcb7913a37d");

$json_velos = json_decode($velos);


$markers = [];

    for($nbInfos = 0; $nbInfos < sizeof($json_velos); $nbInfos++){
        $marker_lat = $json_velos[$nbInfos]->{'position'}->{'lat'};
        var_dump($marker_lat);
        $marker_lon = $json_velos[$nbInfos]->{'position'}->{'lng'};
        var_dump($marker_lon);
        $marker_nom = $json_velos[$nbInfos]->{'name'};
        $marker_bike_stands = $json_velos[$nbInfos]->{'bike_stands'};
        $marker_available_bike_stands = $json_velos[$nbInfos]->{'available_bike_stands'};
        $marker_available_bikes = $json_velos[$nbInfos]->{'available_bikes'};

        $tableau = [$marker_lat,$marker_lon,$marker_nom,$marker_bike_stands,$marker_available_bike_stands,$marker_available_bikes];
        array_push($markers, $tableau);
    }

$jsonmarkers = json_encode($markers);

$html = <<<HTML
        <!doctype html>
        <html>
        <head>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
<!-- Make sure you put this AFTER Leaflet's CSS -->

<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>
        <style>
            #map {     height: 100vh;
                        width: 100vh; }
        </style>

        </head>
        <body>
HTML;

$html .= $proc->transformToXML($xmlDoc);

$html .= <<<HTML
    <div id="map"></div>

    <script type="text/javascript">

       //Coords IP
        var xy = [{$lat}, {$lon}];
        // création de la map avec niveau de zoom
        var map = L.map('map').setView(xy, 6);
        // création du calque images
        L.tileLayer('http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}', {
            maxZoom: 20
        }).addTo(map);

        // ajout d'un marker
        var marker = L.marker(xy).addTo(map);
        // ajout d'un popup
        marker.bindPopup('<h3>Vous êtes ici</h3>');

      
        markers = {$jsonmarkers};

        markers.forEach(function(marker) {
            L.marker([ marker[0] , marker[1] ]).addTo(map).bindPopup("<h3>" + marker[2] + "</h3>" + "</br>" + "Bike stands : " + marker[3] + "</br>" + "Available bike stands : " + marker[4] +"</br>" + "Available bikes : " + marker[5]);
         });

   </script>
   
   
HTML;

echo $html . "</body></html>";
