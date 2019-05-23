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

  $id = $_POST['id'];
  $name = $_POST['name'];
  $curr = $_POST['curr'];
  $period = $_POST['period'];
  $active = $_POST['active'];
  $hours_theory = $_POST['hours_theory'];
  $hours_lab = $_POST['hours_lab'];
  $hours_practice = $_POST['hours_practice'];


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
