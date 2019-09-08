<?php
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, origin");
header('Content-Type: application/json');
require_once dirname(__FILE__).'/utils.php';

$batch_size = 5000;

$state = $_GET['state'] ?? 'NSW';
$routes = '';
if($state === 'NSW'){

  $file = dirname(__FILE__)."/routes.csv";
  $file_data = array_slice(file($file), 0, $batch_size +1);

  $csv = array_map('str_getcsv', $file_data);
  array_walk($csv, function(&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
  });
  array_shift($csv); # remove column header

  $results = [];
  foreach($csv as $key => $route) {
    $route['rating'] = rand(1, 5);
    if($route['route_desc'] !== 'School Buses'){
      $results[$key] = $route;
    }
  }

  $routes = json_encode(array_values($results));
}

if($state === 'ACT'){
  
  $file = dirname(__FILE__)."/routes-act.csv";
  $file_data = array_slice(file($file), 0, $batch_size + 1);

  $csv = array_map('str_getcsv', $file_data);
  array_walk($csv, function(&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
  });
  array_shift($csv); # remove column header


  array_walk($csv, function(&$route) use ($csv) {
    $route['rating'] = rand(1, 5);
    $route['route_short_name'] = str_pad($route['route_short_name'], 3, "0", STR_PAD_LEFT);
  });
  $routes = json_encode($csv);
}


echo $routes;
die();