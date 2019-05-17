<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Middleware;


//echo $_SESSION["authtoken"];

$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
[
'headers' => ['Authorization' => $_SESSION["authtoken"]],
'query' => ['courseid' => $_GET['id']]
]
);


//$data = json_decode($res->getBody());

  //  var_dump($res->json);
//echo $res->getStatusCode();           // 200
//echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
echo $res->getBody()->getContents();           // 200
echo $res->getBody();                 // {"type":"User"...'



//var_export($res->json());
$response = (string) $res->getBody();
$json = json_decode($res->getBody());
$messages = $json->messages;
$data = $json->data->courses;
//$data = $json->data->tasks;
//print_r($course->data);

//echo $data[0]->id;
echo $data[0]->id;

print_r($json);
echo $json;


//$movies = json_decode($response->getBody()->getContents());

 ?>
