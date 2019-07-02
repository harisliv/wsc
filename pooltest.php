<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      //error_reporting(0);
      use Psr\Http\Message\ResponseInterface;
      use GuzzleHttp\Exception\RequestException;

  require 'vendor/autoload.php';
  $client = new GuzzleHttp\Client();

  //echo $_SESSION["authtoken"];
  try {
  $promise = $client->requestAsync('GET', 'http://localhost/shedulerapi/controller/course.php',
  [
  'headers' => ['Authorization' => $_SESSION["authtoken"]]
  ]);

  $promise->then(function (ResponseInterface $response) {
      //$profile = json_decode($response->getBody(), true);
      //print_r($profile);
      // Do something with the profile.
      $body = $response->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      //print_r($json);
      echo $json->data->courses[0]->name;

      //echo "tekifsa " . $response->getStatusCode();
  });
}

catch (GuzzleHttp\Exception\BadResponseException $e) {
    $response = $e->getResponse();
    $responseBodyAsString = (string) $response->getBody();
    $json = json_decode($responseBodyAsString);
    $responsestatuscode = $response->getStatusCode();
    $messages = $json->messages;
}

  $promise->wait();


      headernav();


       ?>


       <?php footernav(); ?>
