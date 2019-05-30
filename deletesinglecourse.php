<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      //error_reporting(0);


      require "vendor/autoload.php";
      use GuzzleHttp\Client;

      //echo $_SESSION["authtoken"];

      $client = new GuzzleHttp\Client();
      $res = $client->request('DELETE', 'http://localhost/shedulerapi/controller/course.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['courseid' => $_SESSION["courseid"]]
      ]
      );


            $body = $res->getBody();
            $string = $body->getContents();
            $json = json_decode($string);

            headernav();


             ?>

             <pre><?php   print_r($json); ?></pre>

             <?php footernav(); ?>
