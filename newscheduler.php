<?php include "header.php"; ?>
<?php include "footer.php"; ?>
<?php session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(0);

headernav();

$weekdb = array("de", "tr", "te", "pe", "pa");
$weekgk = array("Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή");

require "vendor/autoload.php";
use GuzzleHttp\Client;

//echo $_SESSION["authtoken"];
try {

$client = new GuzzleHttp\Client();

$res = $client->request('GET', 'http://localhost/shedulerapi/controller/room_avail.php',
[
'headers' => ['Authorization' => $_SESSION["authtoken"]],
'query' => ['id_acadsem' => $_SESSION["id_acadsem"],
            'available' => 'Y'
           ]
]
);

$body = $res->getBody();
$string = $body->getContents();
$json = json_decode($string);
$data = $json->data->rooms_avail;


$res0 = $client->request('GET', 'http://localhost/shedulerapi/controller/scheduler.php',
[
'headers' => ['Authorization' => $_SESSION["authtoken"]],
'query' => ['id_acadsem' => $_SESSION["id_acadsem"]
           ]
]
);

$body0 = $res0->getBody();
$string0 = $body0->getContents();
$json0 = json_decode($string0);
$data0 = $json0->data->schedulers;

$res4 = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
[
'headers' => ['Authorization' => $_SESSION["authtoken"]]
]
);

$body4 = $res4->getBody();
$string4 = $body4->getContents();
$json4 = json_decode($string4);

$res5 = $client->request('GET', 'http://localhost/shedulerapi/controller/professor.php',
[
'headers' => ['Authorization' => $_SESSION["authtoken"]]
]
);

$body5 = $res5->getBody();
$string5 = $body5->getContents();
$json5 = json_decode($string5);

for ($x = 0; $x < $json->data->rows_returned; $x++) {
  $res2 = $client->request('GET', 'http://localhost/shedulerapi/controller/room.php',
  [
  'headers' => ['Authorization' => $_SESSION["authtoken"]],
  'query' => ['id' => $data[$x]->id_room ]
  ]
  );

  $body2 = $res2->getBody();
  $string2 = $body2->getContents();
  $json2 = json_decode($string2);
  $room_array[$x] = $json2;

  $res3 = $client->request('GET', 'http://localhost/shedulerapi/controller/timeslot.php',
  [
  'headers' => ['Authorization' => $_SESSION["authtoken"]],
  'query' => ['id' => $data[$x]->id_ts ]
  ]
  );

  $body3 = $res3->getBody();
  $string3 = $body3->getContents();
  $json3 = json_decode($string3);
  $timeslot_array[$x] = $json3;
  //echo "<br> Room ID: " . $data[$x]->id_room;
  //echo "<br> json2 room code: " . $room_array[$x]->data->rooms[0]->room_code . "<br>";
  //print_r($json2);
}

for ($x = 0; $x < $json0->data->rows_returned; $x++) {
  $res6 = $client->request('GET', 'http://localhost/shedulerapi/controller/room.php',
  [
  'headers' => ['Authorization' => $_SESSION["authtoken"]],
  'query' => ['id' => $data0[$x]->id_room ]
  ]
  );

  $body6 = $res6->getBody();
  $string6 = $body6->getContents();
  $json6 = json_decode($string6);
  $room_array_sch[$x] = $json6;

  $res7 = $client->request('GET', 'http://localhost/shedulerapi/controller/timeslot.php',
  [
  'headers' => ['Authorization' => $_SESSION["authtoken"]],
  'query' => ['id' => $data0[$x]->id_ts ]
  ]
  );

  $body7 = $res7->getBody();
  $string7 = $body7->getContents();
  $json7 = json_decode($string7);
  $timeslot_array_sch[$x] = $json7;
  //echo "<br> Room ID: " . $data[$x]->id_room;
  //echo "<br> json2 room code: " . $room_array[$x]->data->rooms[0]->room_code . "<br>";
  //print_r($json2);


$res8 = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
[
'headers' => ['Authorization' => $_SESSION["authtoken"]],
'query' => ['id' => $data0[$x]->id_course]
]
);

$body8 = $res8->getBody();
$string8 = $body8->getContents();
$json8 = json_decode($string8);
$course_array[$x] = $json8;

}

if($json0->data->rows_returned > $json->data->rows_returned){
  $loop = $json0->data->rows_returned;
}
else {
  $loop = $json->data->rows_returned;
}


  ?>

<center><h1>Sheduler</h1></center>
  <table class="flat-table flat-table-1">
    <thead>
      <th>ID</th>
      <th>ROOM</th>
      <th>START TIME</th>
      <th>DAY</th>
    </thead>
    <tbody>
      <?php for ($x = 0; $x < $json->data->rows_returned; $x++) { ?>
      <tr>
        <td><?php echo $json->data->rooms_avail[$x]->id; ?></td>
        <td><?php echo $room_array[$x]->data->rooms[0]->lektiko_room; ?></td>
        <td><?php echo $timeslot_array[$x]->data->timeslots[0]->start_time . ":00"; ?></td>
        <td><?php for ($y = 0; $y <= 4; $y++) {
        if($weekdb[$y] === $timeslot_array[$x]->data->timeslots[0]->day){
          echo str_replace($weekdb[$y], $weekgk[$y], $timeslot_array[$x]->data->timeslots[0]->day);
        }}?></td>

      </tr>
    <?php } ?>

    </tbody>
  </table>

  <form action="newschedulerform.php" method="post">

  <table class="flat-table flat-table-1 table table-bordered">
    <thead>
      <th> </th>
       <?php for ($y = 0; $y <= 4; $y++) { ?>
      <th>
        <?php echo $weekgk[$y]; ?>
      </th>
      <?php } ?>
    </thead>
    <tbody>

      <?php for ($st = 8; $st < 11; $st++) { ?>
      <tr>
        <td><?php echo $st; ?></td>
        <?php for ($y = 0; $y <= 4; $y++) { ?>
        <td><?php for ($x = 0; $x < $loop; $x++) {
          if($timeslot_array[$x]->data->timeslots[0]->start_time == $st && $weekdb[$y] === $timeslot_array[$x]->data->timeslots[0]->day) {
            //echo $timeslot_array[$x]->data->timeslots[0]->start_time . ":00";
            //echo str_replace($weekdb[$y], $weekgk[$y], $timeslot_array[$x]->data->timeslots[0]->day);
            ?><div class="form-check">
            <input class="form-check-input" type="radio" name="testtableradio[<?php echo $x;?>]" value="<?php echo $json->data->rooms_avail[$x]->id; ?>" >
            <?php echo $room_array[$x]->data->rooms[0]->lektiko_room; ?>
          </div>
            <?php
          }
            elseif($timeslot_array_sch[$x]->data->timeslots[0]->start_time == $st && $weekdb[$y] === $timeslot_array_sch[$x]->data->timeslots[0]->day) {
              //echo $timeslot_array[$x]->data->timeslots[0]->start_time . ":00";
              //echo str_replace($weekdb[$y], $weekgk[$y], $timeslot_array[$x]->data->timeslots[0]->day);
              echo $room_array_sch[$x]->data->rooms[0]->lektiko_room . "<br>";
              echo $course_array[$x]->data->courses[0]->name . "<br>";
            }
        }
        ?>
        </td>
        <?php } ?>
      </tr>
    <?php } ?>

    </tbody>
  </table>

     <div class='form-group'>
         <label>Course</label>
         <select name='id_course'>
           <?php for ($x = 0; $x < $json4->data->rows_returned; $x++) { ?>
           <option value="<?php print_r($json4->data->courses[$x]->id); ?>">
             <?php print_r($json4->data->courses[$x]->name); ?>
           </option>
         <?php } ?>
         </select>
       </div>

       <input type="radio" name="type_division" value="lab">lab
       <input type="radio" name="type_division" value="theory">theory
       <input type="radio" name="type_division" value="practice">practice
       <br>

       <div class='form-group'>
           <label>Καθηγητής</label>
           <select name='id_prof'>
             <?php for ($x = 0; $x < $json5->data->rows_returned; $x++) { ?>
             <option value="<?php print_r($json5->data->professors[$x]->id); ?>">
               <?php print_r($json5->data->professors[$x]->fullname); ?>
             </option>
           <?php } ?>
           </select>
         </div>


       <br><input type="submit" value="Submit">


      </form>
      <?php
}

catch (GuzzleHttp\Exception\BadResponseException $e) {
$response = $e->getResponse();
$responseBodyAsString = (string) $response->getBody();
$json = json_decode($responseBodyAsString);
$responsestatuscode = $response->getStatusCode();
$messages = $json->messages;
}

footernav(); ?>
