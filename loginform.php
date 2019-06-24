  <?php
  include "header.php";
  session_start();
  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\RequestException;
  use GuzzleHttp\Psr7\Request;
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  error_reporting(0);

  $client = new GuzzleHttp\Client();

  try {


  (empty($_POST['username'])  ? $username = NULL : $username = $_POST['username']);
  (empty($_POST['password'])  ? $password = NULL : $password = $_POST['password']);
  (empty($_POST['id_acadsem'])  ? $_SESSION["id_acadsem"] = NULL : $_SESSION["id_acadsem"] = $_POST['id_acadsem']);

  if(!isset($_SESSION["authtoken"])) {

  $res = $client->request('POST', 'http://localhost/shedulerapi/sessions',
    [
    'json' =>
      [
      'username' => $username,
      'password' => $password
      ]
    ]
    );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $token = $json->data->access_token;
    $sessid = $json->data->session_id;
    $_SESSION["authtoken"]=$token;
    $_SESSION["sessionid"]=$sessid;
  }
  else {

    echo $_SESSION["sessionid"];
    $client3 = new GuzzleHttp\Client();
    $res3 = $client3->request('GET', 'http://localhost/shedulerapi/controller/acad_sem.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );

    $body3 = $res3->getBody();
    $string3 = $body3->getContents();
    $json3 = json_decode($string3);
  }

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
   <center><h1>SIGN UP</h1></center>
   <?php
     if(isset($_SESSION["id_acadsem"])) {
       echo "id: " . $_SESSION["id_acadsem"];
       echo " lektiko: " . $json3->data->acadsems[$x]->lektiko_acadsem;
     }else{
     ?>
   <form action="#" method="post">
   <div class='form-group'>
       <label>acadsem</label>
       <select name='id_acadsem'>
         <?php for ($x = 0; $x <= $json3->data->rows_returned; $x++) { ?>
         <option value="<?php print_r($json3->data->acadsems[$x]->id); ?>">
           <?php print_r($json3->data->acadsems[$x]->lektiko_acadsem); ?>
         </option>
       <?php } ?>
       </select>
     </div>
     <input type="submit" value="Submit">
   </form>
 <?php } ?>

   <center><h1>SESSION INFO</h1></center>
   <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

             <pre><?php print_r($json); ?></pre>
             <br>
             <pre><?php   echo $token; ?></pre>


    <?php footernav(); ?>
