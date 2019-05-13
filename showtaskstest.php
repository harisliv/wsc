<?php
session_start();

require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

echo $_SESSION["authtoken"];
$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'http://localhost/shedulerapi/tasks', [
'headers' => [
'Authorization' => $_SESSION["authtoken"]
]
]
);
echo $res->getStatusCode();           // 200
echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
echo $res->getBody();                 // {"type":"User"...'
var_export($res->json());
 ?>
