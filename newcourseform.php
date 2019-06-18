<?php include "header.php"; ?>
<?php include "footer.php"; ?>

  <?php

  session_start();

  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  error_reporting(0);

  $client = new GuzzleHttp\Client();

  try {

    (empty($_POST['course_id'])  ? $course_id = NULL : $course_id = $_POST['course_id']);
    (empty($_POST['name'])  ? $name = NULL : $name = $_POST["name"] );
    (empty($_POST['curr'])  ? $curr = NULL : $curr = $_POST["curr"] );
    (empty($_POST['period'])  ? $period = NULL : $period = $_POST["period"] );
    (empty($_POST['active'])  ? $active = NULL : $active = $_POST["active"] );
    (empty($_POST['hours_theory'])  ? $hours_theory = NULL : $hours_theory = $_POST["hours_theory"] );
    (empty($_POST['hours_lab'])  ? $hours_lab = NULL : $hours_lab = $_POST["hours_lab"] );
    (empty($_POST['hours_practice'])  ? $hours_practice = NULL : $hours_practice = $_POST["hours_practice"] );


  $res = $client->request('POST', 'http://localhost/shedulerapi/controller/course.php' ,
  [
          'headers' =>
            [
        'Authorization' => $_SESSION["authtoken"]
            ],

          'json' =>
            [
        'course_id' => $course_id,
        'name' => $name,
        'curr' => $curr,
        'period' => $period,
        'active' => $active,
        'hours_theory' => $hours_theory,
        'hours_lab' => $hours_lab,
        'hours_practice' => $hours_practice
            ]
  ]
  );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;
    $data = $json->data->courses;

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
     <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

     <pre>ID : <?php print_r($data[0]->id); ?></pre>
     <br>
     <pre><?php   print_r($json); ?></pre>

     <?php footernav(); ?>
