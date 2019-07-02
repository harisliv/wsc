<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      error_reporting(0);


      require "vendor/autoload.php";
      use GuzzleHttp\Client;
      use GuzzleHttp\Exception\RequestException;
      use GuzzleHttp\Pool;
      use GuzzleHttp\Psr7\Request;
      use GuzzleHttp\Psr7\Response;

      use GuzzleHttp\Promise;
      headernav();
      $weekdb = array("de", "tr", "te", "pe", "pa");
      $weekgk = array("Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή");

      $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost/shedulerapi/controller/']);
      $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];
      $room_avail_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id_acadsem' => $_SESSION["id_acadsem"],'available' => 'Y']];
      $room_header_1 = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id' => $room_avail_array[$x]->id_room ]];
      $timeslot_header_1 = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' =>['id'=>$room_avail_array[$x]->id_ts ]];
      $room_header_2 = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id' => $scheduler_array[$x]->id_room ]];
      $timeslot_header_2 = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' =>['id'=>$scheduler_array[$x]->id_ts ]];
      $course_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id' => $scheduler_array[$x]->id_course]];
      // Initiate each request but do not block
      $promises = [
          'course' => $client->getAsync('course.php', $header),
          'room_avail'  => $client->getAsync('room_avail.php', $room_avail_header),
          'scheduler'  => $client->getAsync('scheduler.php', $header)
      ];

      // Wait on all of the requests to complete. Throws a ConnectException
      // if any of the requests fail
      $results = Promise\unwrap($promises);

      // Wait for the requests to complete, even if some of them fail
      $results = Promise\settle($promises)->wait();

      // You can access each result using the key provided to the unwrap
      // function.
      //echo $results['course']['value']->getStatusCode();
      $body = $results['room_avail']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $room_avail_array = $json->data->rooms_avail;
      $room_avail_rows = $json->data->rows_returned;
      echo "<br> id room: " . $room_avail_array[0]->id_room;

      //print_r($room_avail_array);
      //echo $room_avail_rows;
      //echo $json->data->rows_returned;

      //print_r(json_decode($results['scheduler']['value']->getBody(), true));


      $body = $results['scheduler']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $scheduler_array = $json->data->schedulers;
      $scheduler_rows = $json->data->rows_returned;

      //print_r($scheduler_array);
      //echo $scheduler_rows;


      for ($x = 0; $x < $room_avail_rows; $x++) {
        $promises = [
            'room_1'   => $client->getAsync('room.php', $room_header_1),
            'timeslot_1'  => $client->getAsync('timeslot.php', $timeslot_header_1)
        ];
      }

      $results = Promise\unwrap($promises);

      // Wait for the requests to complete, even if some of them fail
      $results = Promise\settle($promises)->wait();


      $body = $results['room_1']['value']->getBody();
      $string = $body->getContents();
      $room_array = json_decode($string);

      //print_r($room_array);

      $body = $results['timeslot_1']['value']->getBody();
      $string = $body->getContents();
      $timeslot_array = json_decode($string);
      print_r($timeslot_array);

      echo "<br>ts " . $timeslot_array->data->timeslots[2]->start_time;


      for ($x = 0; $x < $scheduler_rows; $x++) {
        $promises = [
            'room_2'   => $client->getAsync('room.php', $room_header_2),
            'timeslot_2'  => $client->getAsync('timeslot.php', $timeslot_header_2),
            'course_2'  => $client->getAsync('course.php', $course_header)
        ];
      }

      $results = Promise\unwrap($promises);

      // Wait for the requests to complete, even if some of them fail
      $results = Promise\settle($promises)->wait();

      // You can access each result using the key provided to the unwrap
      // function.


      $body = $results['room_2']['value']->getBody();
      $string = $body->getContents();
      $room_array_sch = json_decode($string);
      print_r($room_array_sch);

      $body = $results['timeslot_2']['value']->getBody();
      $string = $body->getContents();
      $timeslot_array_sch = json_decode($string);

      $body = $results['course_2']['value']->getBody();
      $string = $body->getContents();
      $course_array = json_decode($string);
      ?>

      <pre> <?php //print_r($json->data->rooms[0]->id); ?> </pre>
      <form action="newschedulerform.php" method="post">

      <table class="table table-bordered">
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
                <input class="form-check-input" type="radio" name="testtableradio[<?php echo $x;?>]" value="<?php echo $room_avail_array[$x]->id; ?>" >
                <?php echo $room_array[$x]->data->rooms[0]->lektiko_room; ?>
              </div>
                <?php
              }
                elseif($timeslot_array_sch[$x]->data->timeslots[0]->start_time == $st && $weekdb[$y] === $timeslot_array_sch[$x]->data->timeslots[0]->day) {
                  //echo $timeslot_array[$x]->data->timeslots[0]->start_time . ":00";
                  //echo str_replace($weekdb[$y], $weekgk[$y], $timeslot_array[$x]->data->timeslots[0]->day);
                  echo $room_array_sch[$x]->data->rooms[0]->lektiko_room;
                  echo $course_array[$x]->data->courses[0]->name;
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








       <?php footernav(); ?>
