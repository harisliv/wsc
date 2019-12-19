<?php include "header.php"; ?>
<?php include "footer.php"; ?>

  <?php

  session_start();

  require "vendor/autoload.php";
  use GuzzleHttp\Client;
  use GuzzleHttp\Exception\RequestException;
  use GuzzleHttp\Psr7\Request;
  use GuzzleHttp\Middleware;
  use GuzzleHttp\Pool;
  use GuzzleHttp\Psr7\Response;
  use Psr\Http\Message\ResponseInterface;

  use GuzzleHttp\Promise;
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  error_reporting(0);

  $client = new GuzzleHttp\Client();

  try {
    $promises = [
        'getonediv'  => $client->getAsync('http://localhost/shedulerapi/controller/room_avail.php',
        [
          'headers' => ['Authorization' => $_SESSION["authtoken"]],
        'query' =>
        [
          'id_acadsem' => $_SESSION["id_acadsem"],
          'available' => "N",
          'learn_sem' => $_SESSION["learn_sem"]
        ]
      ])
        ];

    // Wait on all of the requests to complete. Throws a ConnectException
    // if any of the requests fail
    $results = Promise\unwrap($promises);

    // Wait for the requests to complete, even if some of them fail
    $results = Promise\settle($promises)->wait();

    $body = $results['getonediv']['value']->getBody();
    $string = $body->getContents();
    $json = json_decode($string);
    //$getdivision_array = $json->data->schedulers;
    $rows_returned = $json->data->rows_returned;
    $not_avail_id = $json->data->rooms_avail;


?>
    <table class="table table-bordered">
      <thead>
        <th> </th>
         <?php for ($y = 0; $y <= 4; $y++) { ?>
        <th>
          <?php echo $y; ?>
        </th>
        <?php } ?>
      </thead>
      <tbody>

        <?php for ($st = 1 ; $st <= 13 ; $st++) { ?>

        <tr>
          <td><?php $time = $st + 7; echo $time; ?></td>

          <?php for ($x = 0; $x <= 4; $x++) { ?>
            <td>
              <?php
              $id = $st + $x * 13;
              echo "ts_id:" . $id;
              for ($y = 1; $y <= 9; $y++) {


                for ($z = 0; $z <= $rows_returned; $z++) {

                  if($not_avail_id[$z]->id == $id){
                    echo "kalos";
                  }

                }

                echo " ΑΙΘΟΥΣΑ " . $y . " data: " . $rows_returned . "<br>";
              }?>
            </td>


        <?php } ?>
        </tr>
      <?php }?>

      </tbody>
    </table>
    <?php




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
