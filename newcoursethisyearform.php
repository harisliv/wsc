<?php include "header.php"; ?>
<?php include "footer.php"; ?>

  <?php

  session_start();

  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\RequestException;
  use Psr\Http\Message\ResponseInterface;
  use GuzzleHttp\Pool;
  use GuzzleHttp\Psr7\Request;
  use GuzzleHttp\Psr7\Response;

  use GuzzleHttp\Promise;
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  error_reporting(0);


  headernav();

$url = "http://localhost/shedulerapi/controller/course_this_year.php";
$url2 = "http://httpbin.org/post";

try {

  $nameid = $_POST['id_course'];
  $pieces = explode(",", $nameid);
  echo $pieces[0]; // piece1
  echo $pieces[1];

  $id_course = $pieces[0];
  $name = $pieces[1];
  $learn_sem = $_POST['learn_sem'];
  $id_responsible_prof = $_POST['id_responsible_prof'];
  //$id_acadsem = $_POST['id_acadsem'];
  (empty($_POST['count_div_theory'])  ? $count_div_theory = NULL : $count_div_theory = $_POST['count_div_theory']);
  (empty($_POST['count_div_lab'])  ? $count_div_lab = NULL : $count_div_lab = $_POST['count_div_lab']);
  (empty($_POST['count_div_practice'])  ? $count_div_practice = NULL : $count_div_practice = $_POST['count_div_practice']);

  $client = new GuzzleHttp\Client();

  $promise = $client->postAsync($url, ['headers' => ['Authorization' => $_SESSION["authtoken"]],
  'json' =>['id_course' => $id_course,'name' => $name,'learn_sem' => $learn_sem,'id_responsible_prof' => $id_responsible_prof,'id_acadsem' => $_SESSION["id_acadsem"],'count_div_theory' => $count_div_theory,'count_div_lab' => $count_div_lab,'count_div_practice' => $count_div_practice]]);

      $promise->then(
        function (ResponseInterface $res) {
            echo "kif" . $res->getStatusCode() . "\n";
            $json = json_decode((string)$res->getBody());
            print_r($json);

        },
        function (RequestException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
);

$promise->wait();


  }


  catch (GuzzleHttp\Exception\BadResponseException $e) {
      $response = $e->getResponse();
      $responseBodyAsString = (string) $response->getBody();
      $json = json_decode($responseBodyAsString);
      $responsestatuscode = $response->getStatusCode();
      $messages = $json->messages;
  }


     ?>
     <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

     <pre>ID : <?php print_r($data[0]->id); ?></pre>
     <br>
     <pre><?php   print_r($json); ?></pre>

     <?php footernav();

     ?>
