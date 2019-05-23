<?php include "header.php"; ?>
<?php include "footer.php"; ?>

<?php

require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

$client = new GuzzleHttp\Client();

$fullname = $_POST['fullname'];
$username = $_POST['username'];
$password = $_POST['password'];

$res = $client->request('POST', 'http://localhost/shedulerapi/users',
  [
  'json' =>
    [
    'fullname' => $fullname,
    'username' => $username,
    'password' => $password
    ]
  ]
);


$body = $res->getBody();
$string = $body->getContents();
$json = json_decode($string);

headernav();

 ?>
 <center><h1>USER INFO</h1></center>

 <pre>full name : <?php print_r($json->data->fullname); ?></pre>
 <br>
 <pre><?php   print_r($json); ?></pre>

 <?php footernav(); ?>
