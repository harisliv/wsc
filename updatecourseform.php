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

  ( !empty($_POST['name'])  ? $name = $_POST['name'] : $name = $_SESSION["name"] );
  ( !empty($_POST['curr'])  ? $curr = $_POST['curr'] : $curr = $_SESSION["curr"] );
  ( !empty($_POST['period'])  ? $period = $_POST['period'] : $period = $_SESSION["period"] );
  ( !empty($_POST['active'])  ? $active = $_POST['active'] : $active = $_SESSION["active"] );
  ( !empty($_POST['hours_theory'])  ? $hours_theory = $_POST['hours_theory'] : $hours_theory = $_SESSION["hours_theory"] );
  ( !empty($_POST['hours_lab'])  ? $hours_lab = $_POST['hours_lab'] : $hours_lab = $_SESSION["hours_lab"] );
  ( !empty($_POST['hours_practice'])  ? $hours_practice = $_POST['hours_practice'] : $hours_practice = $_SESSION["hours_practice"] );



  $res = $client->request('PATCH', 'http://localhost/shedulerapi/controller/course.php' ,
  [
          'headers' =>  ['Authorization' => $_SESSION["authtoken"]],
          'query' => ['courseid' => $_SESSION["courseid"]],
          'json' =>
                  [
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


    //$response = (string) $res->getBody();
    //$json = json_decode($response);

    $body = $res->getBody();
    $string = $body->getContents();
    $json = json_decode($string);
    $data = $json->data->courses;
    $messages = $json->messages;

    headernav();

     ?>
     <center><h1><?php echo $messages[0]; ?></h1></center>

     <pre>ID : <?php print_r($data[0]->id); ?></pre>
     <br>
     <pre><?php   print_r($json); ?></pre>

     <?php footernav(); ?>
