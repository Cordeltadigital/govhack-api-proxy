<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, origin");
// header('Content-Type: application/json');

set_time_limit(0);// to infinity for example

require_once 'vendor/autoload.php';

$url = 'https://api.transport.nsw.gov.au/v1/gtfs/realtime/buses';
$ch = curl_init();

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds

$request_headers = array();

$request_headers[] = 'Accept: application/x-google-protobuf';
$request_headers[] = 'Authorization: apikey DtTYD2N3HI0sdSG5P52rnw4Sq58EsvYic97f';


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

$data = curl_exec($ch);
curl_close($ch);  

use transit_realtime\FeedMessage;

$feed = new FeedMessage();
$feed->parse($data);

var_dump($feed);
die();
foreach ($feed->getEntityList() as $entity) {
  
  echo '<pre>';
  print_r($entity);
  print_r($entity->toArray());
  echo '</pre>';
}