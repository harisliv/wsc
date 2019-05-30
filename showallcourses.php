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
      $res = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]]
      ]
      );



      $body = $res->getBody();
      $string = $body->getContents();
      $json = json_decode($string);

      headernav();

       ?>
       <center><h1>SHOW ALL COURSES</h1></center>
       <pre><?php   print_r($json); ?></pre>

       <?php footernav(); ?>
