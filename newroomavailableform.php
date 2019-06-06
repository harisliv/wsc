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

  $client = new GuzzleHttp\Client();

  $id_room = $_POST['id_room'];
  $id_ts = $_POST['id_ts'];
  $id_acadsem = $_POST['id_acadsem'];


  $res = $client->request('POST', 'http://localhost/shedulerapi/controller/room_avail.php' ,
  [
          'headers' =>
            [
        'Authorization' => $_SESSION["authtoken"]
            ],

          'json' =>
            [
        'id_room' => $id_room,
        'id_ts' => $id_ts,
        'id_acadsem' => $id_acadsem,
        'available' => "Y"
            ]
  ]
  );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;
    $data = $json->data->rooms_avail;

    headernav();

     ?>
     <center><h1><?php echo $messages[0]; ?></h1></center>

     <pre>ID : <?php print_r($data[0]->id); ?></pre>
     <br>
     <pre><?php   print_r($json); ?></pre>

     <?php footernav(); ?>
