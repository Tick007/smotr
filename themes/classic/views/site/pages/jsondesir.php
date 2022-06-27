<?php

/*
$js = '[{"station":{"name":"UUMO","type":1,"status":50,"id":7325,"coord":{"lon":37.5,"lat":55.5}},"distance":63.995,"last":{"main":{"temp":303.15,"pressure":1012,"humidity":48},"wind":{"speed":6,"deg":210},"visibility":{"distance":10000,"prefix":1},"calc":{"dewpoint":291.15,"humidex":309.05,"heatindex":304.15},"clouds":[{"distance":1311,"condition":"BKN"}],"dt":1470137400}},{"station":{"name":"UUWW","type":1,"status":50,"id":7329,"coord":{"lon":37.2615,"lat":55.5915}},"distance":67.823,"last":{"main":{"temp":302.15,"pressure":1012,"humidity":51},"wind":{"speed":5,"deg":200},"visibility":{"distance":10000,"prefix":1},"calc":{"dewpoint":291.15,"humidex":308.05,"heatindex":302.15},"clouds":[{"distance":1463,"condition":"BKN"}],"dt":1470137400}},{"station":{"name":"UUWW","type":2,"status":0,"id":37653,"coord":{"lon":37.2693,"lat":55.598}},"distance":68.645,"last":{"main":{"temp":303.15,"humidity":48,"pressure":1012},"wind":{"speed":5.65,"gust":5.65,"deg":202},"dt":1470140151}}]';
*/

$js = '{"coord":{"lon":-0.13,"lat":51.51},"weather":[{"id":802,"main":"Clouds","description":"light cloudly","icon":"03d"}],"base":"cmc stations","main":{"temp":22.66,"pressure":1007,"humidity":53,"temp_min":21,"temp_max":25},"wind":{"speed":6.7,"deg":240,"gust":11.8},"clouds":{"all":40},"dt":1470231439,"sys":{"type":1,"id":5091,"message":0.0032,"country":"GB","sunrise":1470198518,"sunset":1470253413},"id":2643743,"name":"London","cod":200}';

echo $js;

$obj = json_decode($js);
echo '<pre>';
print_r($obj);

echo '</pre>';
?>