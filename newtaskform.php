<?php
session_start();

require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Middleware;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$client = new GuzzleHttp\Client();

$id = $_POST['id'];
$name = $_POST['name'];
$curr = $_POST['curr'];
$period = $_POST['period'];
$active = $_POST['active'];
$hours_theory = $_POST['hours_theory'];
$hours_lab = $_POST['hours_lab'];
$hours_practice = $_POST['hours_practice'];


$res = $client->request('POST', 'http://localhost/shedulerapi/controller/course.php' ,
[
        'headers' =>
          [
      'Authorization' => $_SESSION["authtoken"]
          ],

        'json' =>
          [
      'id' => $id,
      'name' => $name,
      'curr' => $curr,
      'active' => $active,
      'hours_theory' => $hours_theory,
      'hours_lab' => $hours_lab,
      'hours_practice' => $hours_practice
          ]
]
);

echo $res->getStatusCode();           // 200
//echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
echo $res->getBody();                 // {"type":"User"...'
  //return $res->getBody()->getContents();

  $response = (string) $res->getBody();
  $json = json_decode($response);
  $messages = $json->messages;
  $data = $json->data->courses;
  //print_r($data->id);
  echo $data[0]->id;

  print_r($messages);
  echo $messages[0];
  //$data = json_decode($res->getBody());
//echo $data;
/*
echo $res->getBody();                 // {"type":"User"...'

  $response = (string) $res->getBody();
  $json = json_decode($response);
  $token = $json->data->tasks;
  echo $token;

$body = $res->getBody();
echo $body->getContents(); // -->nothing

// Rewind the stream
$body->rewind();
echo $body->getContents(); // -->The request body :)


//echo $res->getStatusCode();           // 200
//echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
//echo $res->getBody();                 // {"type":"User"...'

$response = (string) $res->getBody();
  $json = json_decode($response);
  $token = $json->json->title;
  echo $token;
  //setcookie("TestCookie", $token);


//echo var_export($res->getBody()->json());
*/

?>
