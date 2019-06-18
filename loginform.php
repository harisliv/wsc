  <?php
  include "header.php";
  session_start();
  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\RequestException;
  use GuzzleHttp\Psr7\Request;

  $client = new GuzzleHttp\Client();

  try {

  (empty($_POST['username'])  ? $username = NULL : $username = $_POST['username']);
  (empty($_POST['password'])  ? $password = NULL : $password = $_POST['password']);


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
    $sessid = $json->data->session_id;
    $_SESSION["authtoken"]=$token;
    $_SESSION["sessionid"]=$sessid;

  }


  catch (GuzzleHttp\Exception\BadResponseException $e) {
      $response = $e->getResponse();
      $responseBodyAsString = (string) $response->getBody();
      $json = json_decode($responseBodyAsString);
      $responsestatuscode = $response->getStatusCode();
      $messages = $json->messages;
  }

    headernav();


   ?>
   <center><h1>SESSION INFO</h1></center>
   <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

             <pre><?php print_r($json); ?></pre>
             <br>
             <pre><?php   echo $token; ?></pre>


    <?php footernav(); ?>
