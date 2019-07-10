<?php include "header.php"; ?>
<?php include "footer.php"; ?>

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
  error_reporting(0);
  headernav();

  $client = new GuzzleHttp\Client();

  try {
    //echo "testtableradio: " . $_POST['testtableradio'];

 foreach($_POST['testtableradio'] as $option_num => $option_val){
    echo $option_num." ".$option_val."<br>";


  (empty($_POST['id_room_avail'])  ? $id_room_avail = NULL : $id_room_avail = $_POST['id_room_avail']);
  (empty($_POST['id_course'])  ? $id_course = NULL : $id_course = $_POST['id_course']);
  (empty($_POST['type_division'])  ? $type_division = NULL : $type_division = $_POST['type_division']);
  (empty($_POST['id_prof'])  ? $id_prof = NULL : $id_prof = $_POST['id_prof']);
  (empty($_POST['division_str'])  ? $division_str = NULL : $division_str = $_POST['division_str']);
  //echo $id_prof;
  $client = new GuzzleHttp\Client();
  $res = $client->request('GET', 'http://localhost/shedulerapi/controller/room_avail.php',
  [
  'headers' => ['Authorization' => $_SESSION["authtoken"]],
  'query' => ['id' => $option_val]
  ]
  );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;
    $data = $json->data->rooms_avail;
    //print_r($json);
    //echo "<br> id room: " . $data[0]->id_room;
    //echo "<br> id ts: " . $data[0]->id_ts;




    $client1 = new GuzzleHttp\Client();
    $res1 = $client1->request('POST', 'http://localhost/shedulerapi/controller/scheduler.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]],

      'json' =>
        [
          'id_course' => $id_course,
          'id_acadsem' => $_SESSION["id_acadsem"],
          'type_division' => $type_division,
          'lektiko_division' => "tha doume pws",
          'id_prof' => $id_prof,
          'id_room' => $data[0]->id_room,
          'id_ts' => $data[0]->id_ts,
          'division_str' => $division_str
        ]
    ]
    );


    $response1 = (string) $res1->getBody();
    $json1 = json_decode($response1);
    $messages1 = $json1->messages;

    if($type_division === "theory"){


      $client2 = new GuzzleHttp\Client();
      $res2 = $client2->request('PATCH', 'http://localhost/shedulerapi/controller/room_avail.php',
      [
        'headers' => ['Authorization' => $_SESSION["authtoken"]],
        'query' => ['id_ts' => $data[0]->id_ts],
        'json' =>  ['available' => 'N']
      ]
      );


      $response2 = (string) $res2->getBody();
      $json2 = json_decode($response2);
      $messages2 = $json2->messages;
    }
    else{

    $client2 = new GuzzleHttp\Client();
    $res2 = $client2->request('PATCH', 'http://localhost/shedulerapi/controller/room_avail.php',
    [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['id' => $option_val],
      'json' =>  ['available' => 'N']
    ]
    );


    $response2 = (string) $res2->getBody();
    $json2 = json_decode($response2);
    $messages2 = $json2->messages;
  }

  ?>
    <pre><?php   print_r($json1); ?></pre>
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
