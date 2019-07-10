  <?php include "header.php"; ?>
  <?php include "footer.php"; ?>

    <?php

    session_start();

    require "vendor/autoload.php";
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use GuzzleHttp\Pool;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Psr7\Response;
    use Psr\Http\Message\ResponseInterface;
    use GuzzleHttp\Promise;
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    error_reporting(0);
    headernav();

    $client = new GuzzleHttp\Client();

    try {
      //echo "testtableradio: " . $_POST['testtableradio'];
      (empty($_POST['id_room_avail'])  ? $id_room_avail = NULL : $id_room_avail = $_POST['id_room_avail']);
    (empty($_POST['id_course'])  ? $id_course = NULL : $id_course = $_POST['id_course']);
    (empty($_POST['type_division'])  ? $type_division = NULL : $type_division = $_POST['type_division']);
    (empty($_POST['id_prof'])  ? $id_prof = NULL : $id_prof = $_POST['id_prof']);
    //echo $id_prof;

    $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];



  foreach($_POST['testtableradio'] as $option_num => $option_val){
    echo $option_num." ".$option_val."<br>";
    $room_avail_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id_acadsem' => $_SESSION["id_acadsem"],'id' => $option_val ]];

    $scheduler_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'json' =>['id_course' => $id_course,'id_acadsem' => $_SESSION["id_acadsem"],'type_division' => $type_division,'lektiko_division' => "tha doume pws",'id_prof' => $id_prof,'id_room' => $room_avail_array[0]->id_room ,
    'division_str' => "ab12"]];

    $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost/shedulerapi/controller/']);

    $promise = $client->getAsync('room_avail.php', $room_avail_header);
    $promise->then(
      function (ResponseInterface $res){
        global $room_id;
        global $ts_id;
        echo "room_avail" . $res->getStatusCode() . "\n";
        $json = json_decode((string)$res->getBody());
        echo "<br><br>";
          $room_avail_array = $json->data->rooms_avail;
          $room_avail_rows = $json->data->rows_returned;
          $room_id = $room_avail_array[0]->id_room;
          $ts_id = $room_avail_array[0]->id_ts;

      },
      function (RequestException $e) {
          echo $e->getMessage() . "\n";
          echo $e->getRequest()->getMethod();
      }
  );

  $promise->wait();

  print_r($json);

  $promise = $client->postAsync('scheduler.php',
  ['headers' => ['Authorization' => $_SESSION["authtoken"]],
  'json' =>
  [
    'id_course' => $id_course,
    'id_acadsem' => $_SESSION["id_acadsem"],
    'type_division' => $type_division,
    'lektiko_division' => "tha doume pws",
    'id_prof' => $id_prof,
    'id_room' => $room_id,
    'id_ts' => $ts_id,
    'division_str' => "ab12"
    ]
  ]);
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


      if($type_division === "theory"){


        $promise = $client->patchAsync('room_avail.php',
        [
          'headers' => ['Authorization' => $_SESSION["authtoken"]],
          'query' => ['id_ts' => $ts_id],
          'json' =>  ['available' => 'N']
        ]
        );

        $promise->then(
          function (ResponseInterface $res) {
            echo "theory" . $res->getStatusCode() . "\n";
            $json = json_decode((string)$res->getBody());
          },
          function (RequestException $e) {
              echo $e->getMessage() . "\n";
              echo $e->getRequest()->getMethod();
          }
        );
        $promise->wait();
      }
      else{

        $promise = $client->patchAsync('room_avail.php',
      [
        'headers' => ['Authorization' => $_SESSION["authtoken"]],
        'query' => ['id' => $option_val],
        'json' =>  ['available' => 'N']
      ]
      );

      $promise->then(
        function (ResponseInterface $res) {
          echo "other" . $res->getStatusCode() . "\n";
          $json = json_decode((string)$res->getBody());
        },
        function (RequestException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
      );
      $promise->wait();
    }


    }
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

       <pre> <?php //print_r($data1[0]->id); ?></pre>
       <br>

       <?php footernav(); ?>
