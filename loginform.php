<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

$client = new GuzzleHttp\Client();

$username = $_POST['username'];
$password = $_POST['password'];

$res = $client->request('POST', 'http://localhost/v1/sessions',
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
  setcookie("TestCookie", $token);


//echo var_export($res->getBody()->json());
 ?>

<form action="newtask.php" method="post">
  <input type="submit" value="Submit">
