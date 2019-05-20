<?php
session_start();
require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

$client = new GuzzleHttp\Client();

$username = $_POST['username'];
$password = $_POST['password'];

$res = $client->request('POST', 'http://localhost/shedulerapi/sessions',
  [
  'json' =>
    [
    'username' => $username,
    'password' => $password
    ]
  ]
);

//echo $res->getStatusCode();           // 200
//echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
echo $res->getBody();                 // {"type":"User"...'

  $response = (string) $res->getBody();
  $json = json_decode($response);
  $token = $json->data->access_token;
  echo $token;

  $_SESSION["authtoken"]=$token;
  //setcookie("TestCookie", $token);


//echo var_export($res->getBody()->json());
 ?>

<form action="showtaskstest.php" method="post">
  <div class='form-group'>
      <label for='id'>Course ID</label>
      <input type='id' class='form-control' name='id' placeholder='Enter Course ID'>
  </div>
  <input type="submit" value="Submit">
