  <?php
  include "header.php";
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


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $token = $json->data->access_token;
    $_SESSION["authtoken"]=$token;

    headernav();


   ?>
   <center><h1>SESSION INFO</h1></center>

             <pre><?php print_r($json); ?></pre>
             <br>
             <pre><?php   echo $token; ?></pre>


    <?php footernav(); ?>
