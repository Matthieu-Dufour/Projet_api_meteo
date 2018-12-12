<?php

$opts = array('http' => array('proxy'=> 'tcp://www-cache.iutnc.univ-lorraine.fr:3128/', 'request_fulluri'=> true));

$context = stream_context_create($opts); 

    $ip = file_get_contents('http://ip-api.com/xml/', false, $context);

    $xml_ip = simplexml_load_string($ip);

    $lat = $xml_ip->lat->__toString();
    $lon = $xml_ip->lon->__toString();

    $meteo = file_get_contents("http://www.infoclimat.fr/public-api/gfs/xml?_ll=" . $lat . "," . $lon . "&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2", false, $context);
    

    $xmlDoc =  new DOMDocument();
    $xmlDoc->loadXML($meteo);

    $xslDoc = new DOMDocument();
    $xslDoc->load("meteo.xsl");

    $proc = new XSLTProcessor();
    $proc->importStylesheet($xslDoc);
    echo $proc->transformToXML($xmlDoc);
    





