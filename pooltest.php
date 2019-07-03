<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      //error_reporting(0);
      use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

  require 'vendor/autoload.php';
  $client = new GuzzleHttp\Client();
  headernav();

  //echo $_SESSION["authtoken"];


    $client = new GuzzleHttp\Client();
  echo $_SESSION["authtoken"];

  $requests = function ($total) {
      $uri = 'http://localhost/shedulerapi/controller/room.php';
      $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];
      for ($i = 0; $i < $total; $i++) {
          yield new Request('GET', $uri, $header);
      }
  };

  $pool = new Pool($client, $requests(7), [
      'concurrency' => 7,
      'fulfilled' => function ($response, $index) {
          // this is delivered each successful response
          echo "tekifsa " . $response->getStatusCode();
          $body = $response->getBody();
          $string = $body->getContents();
          $json = json_decode($string);
          //print_r($json);
          echo $json->data->rooms[0]->id;
      },
      'rejected' => function ($reason, $index) {
        echo $reason->getMessage();
        echo $index;
      },
  ]);

  // Initiate the transfers and create a promise
  $promise = $pool->promise();

  // Force the pool of requests to complete.
  $promise->wait();


      //$profile = json_decode($response->getBody(), true);
      //print_r($profile);
      // Do something with the profile.






        footernav(); ?>
