<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      error_reporting(0);


      require "vendor/autoload.php";
      use GuzzleHttp\Client;

      $client = new GuzzleHttp\Client();

      try {

      (empty($_POST['day'])  ? $day = NULL : $day = $_POST["day"] );
      (empty($_POST['start_time'])  ? $start_time = NULL : $start_time = $_POST["start_time"] );
      (empty($_POST['room_code'])  ? $room_code = NULL : $room_code = $_POST["room_code"] );

      $res = $client->request('GET', 'http://localhost/shedulerapi/controller/room_avail.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['day' => $day, 'start_time' => $start_time, 'room_code' => $room_code],
      ]
      );



      $body = $res->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $messages = $json->messages;

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
       <center><h1>SHOW ROOMS AVAILABLE AT THIS DAY AND TIME</h1></center>
       <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

       <pre><?php   print_r($json); ?></pre>

       <?php footernav(); ?>
