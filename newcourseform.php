<?php include "header.php"; ?>
<?php include "footer.php"; ?>

  <?php

  session_start();

  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  //error_reporting(0);

  $client = new GuzzleHttp\Client();

    (empty($_POST['id'])  ? $id = NULL : $id = $_POST['id']);
    echo $id;
    (empty($_POST['name'])  ? $name = NULL : $name = $_POST["name"] );
    echo $name;
    (empty($_POST['curr'])  ? $curr = NULL : $curr = $_POST["curr"] );
    echo $curr;
    (empty($_POST['period'])  ? $period = NULL : $period = $_POST["period"] );
    echo $period;
    (empty($_POST['active'])  ? $active = NULL : $active = $_POST["active"] );
    echo $active;
    (empty($_POST['hours_theory'])  ? $hours_theory = NULL : $hours_theory = $_POST["hours_theory"] );
    echo $hours_theory;
    (empty($_POST['hours_lab'])  ? $hours_lab = NULL : $hours_lab = $_POST["hours_lab"] );
    echo $hours_lab;
    (empty($_POST['hours_practice'])  ? $hours_practice = NULL : $hours_practice = $_POST["hours_practice"] );
    echo $hours_practice;


  $res = $client->request('POST', 'http://localhost/shedulerapi/controller/course.php' ,
  [
          'headers' =>
            [
        'Authorization' => $_SESSION["authtoken"]
            ],

          'json' =>
            [
        'id' => $id,
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

    headernav();

     ?>
     <center><h1><?php echo $messages[0]; ?></h1></center>

     <pre>ID : <?php print_r($data[0]->id); ?></pre>
     <br>
     <pre><?php   print_r($json); ?></pre>

     <?php footernav(); ?>
