<?php
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, origin");
header('Content-Type: application/json');
require_once dirname(__FILE__).'/utils.php';

$state = $_GET['state'] ?? 'NSW';

$query_route_id = $_GET['id'] ?? '';

if(strtolower($state) === 'nsw'){

  $file = dirname(__FILE__)."/routes-nsw.csv";

  $csv = array_map('str_getcsv', file($file));
  array_walk($csv, function(&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
  });
  array_shift($csv); # remove column header

  foreach($csv as $key => $route) {
    if($route['route_id'] === $query_route_id){
      echo json_encode($route);
      die();
    }
  }
}

if(strtolower($state) === 'act'){
  $file = dirname(__FILE__)."/routes-act.csv";
  
  $csv = array_map('str_getcsv', file($file));
  array_walk($csv, function(&$a) use ($csv, $query_route_id) {
    $a = array_combine($csv[0], $a);
  });
  array_shift($csv); # remove column header


  array_walk($csv, function(&$route) use ($csv, $query_route_id) {
    if($route['route_id'] === $query_route_id ){
      $route['route_short_name'] = str_pad($route['route_short_name'], 3, "0", STR_PAD_LEFT);
      echo json_encode($route);
      die();
    }
  });
  
}

