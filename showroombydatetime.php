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

      //echo $_SESSION["authtoken"];

      $client = new GuzzleHttp\Client();
      $res = $client->request('GET', 'http://localhost/shedulerapi/controller/room.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['day' => $_POST['day'], 'start_time' => $_POST['start_time']]
      ]
      );



      $body = $res->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $messages = $json->messages;



      headernav();


       ?>
       <center><h1>SHOW ROOMS AVAILABLE AT THIS DAY AND TIME</h1></center>
       <center><h1><?php echo $messages[0]; ?></h1></center>

       <pre><?php   print_r($json); ?></pre>

       <?php footernav(); ?>
