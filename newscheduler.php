    <?php include "header.php"; ?>
    <?php include "footer.php"; ?>
    <?php session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    error_reporting(0);

    require "vendor/autoload.php";
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\ClientException;

    //echo $_SESSION["authtoken"];

    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'http://localhost/shedulerapi/controller/room_avail.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]],
    'query' => ['available' => 'Y']
    ]
    );

    $body = $res->getBody();
    $string = $body->getContents();
    $json = json_decode($string);
    $data = $json->data->rooms_avail;


headernav();

    for ($x = 0; $x < $json->data->rows_returned; $x++) {
    $client2 = new GuzzleHttp\Client();
    $res2 = $client2->request('GET', 'http://localhost/shedulerapi/controller/room.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]],
    'query' => ['id' => $data[$x]->id_room ]
    ]
    );
    echo $data[$x]->id_room;
    $body2 = $res2->getBody();
    $string2 = $body2->getContents();
    $json2 = json_decode($string2);

    $room_array[$x] = $json2;

    $client3 = new GuzzleHttp\Client();
    $res3 = $client3->request('GET', 'http://localhost/shedulerapi/controller/timeslot.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]],
    'query' => ['id' => $data[$x]->id_ts ]
    ]
    );
    echo $data[$x]->id_ts;


    $body3 = $res3->getBody();
    $string3 = $body3->getContents();
    $json3 = json_decode($string3);
    $timeslot_array[$x] = $json3;

}
      ?>

  <center><h1>SIGN UP</h1></center>

     <form action="newcoursethisyearform.php" method="post">

       <div class='form-group'>
           <label>id_course</label>
           <select name='id_course'>
             <?php for ($x = 0; $x < $json->data->rows_returned; $x++) { ?>
             <option value="<?php print_r($json->data->rooms_avail[$x]->id); ?>">
               <?php
                    echo "Room Code: ";
                    print_r($room_array[$x]->data->rooms[$x]->room_code);
                    echo " Start Time: ";
                    print_r($timeslot_array[$x]->data->timeslots[$x]->start_time);
                    echo " Day: ";
                    print_r($timeslot_array[$x]->data->timeslots[$x]->day);
                     ?>
             </option>
           <?php } ?>
           </select>
         </div>


          </form>

  <?php footernav(); ?>
