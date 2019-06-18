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

try {

  $id_course = $_POST['id_course'];
  $id_responsible_prof = $_POST['id_responsible_prof'];
  $id_acadsem = $_POST['id_acadsem'];
  (empty($_POST['count_div_theory'])  ? $count_div_theory = NULL : $count_div_theory = $_POST['count_div_theory']);
  (empty($_POST['count_div_lab'])  ? $count_div_lab = NULL : $count_div_lab = $_POST['count_div_lab']);
  (empty($_POST['count_div_practice'])  ? $count_div_practice = NULL : $count_div_practice = $_POST['count_div_practice']);



  $res = $client->request('POST', 'http://localhost/shedulerapi/controller/course_this_year.php' ,
  [
          'headers' =>
            [
        'Authorization' => $_SESSION["authtoken"]
            ],

          'json' =>
            [
        'id_course' => $id_course,
        'id_responsible_prof' => $id_responsible_prof,
        'id_acadsem' => $id_acadsem,
        'count_div_theory' => $count_div_theory,
        'count_div_lab' => $count_div_lab,
        'count_div_practice' => $count_div_practice
            ]
  ]
  );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;
    $data = $json->data->rooms_avail;

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

     <?php footernav();

     ?>
