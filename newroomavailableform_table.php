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
  //error_reporting(0);

  $client = new GuzzleHttp\Client();

  try {
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

        <?php for ($x = 0; $x <= 4; $x++) {



          ?>
          <?php for ($st = 1 + $x*13 ; $st <= 13 + $x*13 ; $st++) { ?>

        <tr>

          <td><?php echo 8++; ?></td>
          <?php for ($y = 0; $y <= 4; $y++) {
             for ($y = 1; $y <= 9; $y++) {
            ?>
          <td>

          </td>
        <?php } } ?>
        </tr>
      <?php } }?>

      </tbody>
    </table>
    <?php

  $res = $client->request('POST', 'http://localhost/shedulerapi/controller/room_avail.php' ,
  [
          'headers' =>
            [
        'Authorization' => $_SESSION["authtoken"]
            ],

          'json' =>
            [
        'id_room' => $y,
        'id_ts' => $st,
        'id_acadsem' => 1,
        'available' => "Y",
        'learn_sem' => "B"
            ]
  ]
  );


    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;
    $data = $json->data->rooms_avail;

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
     <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>

     <pre>ID : <?php print_r($data[0]->id); ?></pre>
     <br>
     <pre><?php   print_r($json); ?></pre>

     <?php footernav(); ?>
