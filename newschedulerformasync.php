<?php include "header.php"; ?>
<?php include "footer.php"; ?>

  <?php

  session_start();

  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\RequestException;
  use GuzzleHttp\Pool;
  use GuzzleHttp\Psr7\Request;
  use GuzzleHttp\Psr7\Response;
  use Psr\Http\Message\ResponseInterface;
  use GuzzleHttp\Promise;
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  //error_reporting(0);
  headernav();

  $client = new GuzzleHttp\Client();

  try {
    //echo "testtableradio: " . $_POST['testtableradio'];
    (empty($_POST['id_room_avail'])  ? $id_room_avail = NULL : $id_room_avail = $_POST['id_room_avail']);
  (empty($_POST['id_course'])  ? $id_course = NULL : $id_course = $_POST['id_course']);
  (empty($_POST['type_division'])  ? $type_division = NULL : $type_division = $_POST['type_division']);
  (empty($_POST['id_prof'])  ? $id_prof = NULL : $id_prof = $_POST['id_prof']);
  //echo $id_prof;

  $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];
  $room_avail_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id_acadsem' => $_SESSION["id_acadsem"],'id' => $option_val ]];
  $scheduler_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'json' =>['id_course' => $id_course,'id_acadsem' => $_SESSION["id_acadsem"],'type_division' => $type_division,'lektiko_division' => "tha doume pws",'id_prof' => $id_prof,'id_room' => $room_avail_array[0]->id_room ,'id_room' => $room_avail_array[0]->id_room,
  'division_str' => "ab12"]];

foreach($_POST['testtableradio'] as $option_num => $option_val){
  echo $option_num." ".$option_val."<br>";

  $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost/shedulerapi/controller/']);

  $promise = $client->getAsync('room_avail.php', $room_avail_header);
  $promise->then(
    function (ResponseInterface $res) {
        echo $res->getStatusCode() . "\n";
        $body = $res->getBody();
        $string = $body->getContents();
        $json = json_decode($string);
        $room_avail_array = $json->data->rooms_avail;
        $room_avail_rows = $json->data->rows_returned;
        print_r($json);

    },
    function (RequestException $e) {
        echo $e->getMessage() . "\n";
        echo $e->getRequest()->getMethod();
    }
);

$promise = $client->postAsync('scheduler.php', $room_avail_header);
$promise->then(
  function (ResponseInterface $res) {
      echo $res->getStatusCode() . "\n";
      $body = $res->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      print_r($json);
  },
  function (RequestException $e) {
      echo $e->getMessage() . "\n";
      echo $e->getRequest()->getMethod();
  }
);


    if($type_division === "theory"){


      $res = $client->request('PATCH', 'http://localhost/shedulerapi/controller/room_avail.php',
      [
        'headers' => ['Authorization' => $_SESSION["authtoken"]],
        'query' => ['id_ts' => $room_avail_array[0]->id_ts],
        'json' =>  ['available' => 'N']
      ]
      );


      $response = (string) $res->getBody();
      $json = json_decode($response);
      $messages = $json->messages;
    }
    else{

    $res = $client->request('PATCH', 'http://localhost/shedulerapi/controller/room_avail.php',
    [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['id' => $option_val],
      'json' =>  ['available' => 'N']
    ]
    );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;
  }

  ?>
    <pre><?php   //print_r($json1); ?></pre>
<?php
  }
}

  catch (GuzzleHttp\Exception\BadResponseException $e) {
      $response = $e->getResponse();
      $responseBodyAsString = (string) $response->getBody();
      $json = json_decode($responseBodyAsString);
      $responsestatuscode = $response->getStatusCode();
      $messages = $json->messages;
  }


     ?>
     <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

     <pre> <?php //print_r($data1[0]->id); ?></pre>
     <br>

     <?php footernav(); ?>
