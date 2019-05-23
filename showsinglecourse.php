<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      //error_reporting(0);


      require "vendor/autoload.php";
      use GuzzleHttp\Client;

      //echo $_SESSION["authtoken"];

      $client = new GuzzleHttp\Client();
      $res = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['courseid' => $_POST['id']]
      ]
      );



      $body = $res->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $_SESSION["courseid"]=$_POST['id'];

      $_SESSION["name"]=$json->data->courses[0]->name;
      $_SESSION["curr"]=$json->data->courses[0]->curr;
      $_SESSION["period"]=$json->data->courses[0]->period;
      $_SESSION["active"]=$json->data->courses[0]->active;
      $_SESSION["hours_theory"]=$json->data->courses[0]->hours_theory;
      $_SESSION["hours_lab"]=$json->data->courses[0]->hours_lab;
      $_SESSION["hours_practice"]=$json->data->courses[0]->hours_practice;

      //echo $_SESSION["name"];
      //echo $_SESSION["curr"];

      headernav();


       ?>
       <center><h1>SHOW COURSE BY ID</h1></center>

       <pre>ID : <?php print_r($json->data->courses[0]->id); ?></pre>
       <br>
       <center><h1>UPDATE COURSE</h1></center>
       <form action="updatecourse.php" method="post">
         <input type="submit" value="Submit">
       <pre><?php   print_r($json); ?></pre>

       <?php footernav(); ?>
