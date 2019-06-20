      <?php include "header.php"; ?>
      <?php include "footer.php"; ?>
      <?php session_start();
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      error_reporting(0);

      require "vendor/autoload.php";
      use GuzzleHttp\Client;

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



      for ($x = 0; $x < $json->data->rows_returned; $x++) {
      $client2 = new GuzzleHttp\Client();
      $res2 = $client2->request('GET', 'http://localhost/shedulerapi/controller/room.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['id' => $data[$x]->id_room ]
      ]
      );
      //echo "<br> Room ID: " . $data[$x]->id_room;
      $body2 = $res2->getBody();
      $string2 = $body2->getContents();
      $json2 = json_decode($string2);
      $room_array[$x] = $json2;
      //echo "<br> json2 room code: " . $room_array[$x]->data->rooms[0]->room_code . "<br>";
      //print_r($json2);


      $client3 = new GuzzleHttp\Client();
      $res3 = $client3->request('GET', 'http://localhost/shedulerapi/controller/timeslot.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['id' => $data[$x]->id_ts ]
      ]
      );
      //echo "<br> TIME SLOT ID: " . $data[$x]->id_ts;


      $body3 = $res3->getBody();
      $string3 = $body3->getContents();
      $json3 = json_decode($string3);
      $timeslot_array[$x] = $json3;
      //echo "<br> json3 time slot start time: " . $timeslot_array[$x]->data->timeslots[0]->start_time . "<br>";
      //echo "<br> json3 time slot day: " . $timeslot_array[$x]->data->timeslots[0]->day . "<br>";
      //print_r($json3);

      }

      $client4 = new GuzzleHttp\Client();
      $res4 = $client4->request('GET', 'http://localhost/shedulerapi/controller/course.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]]
      ]
      );

      $body4 = $res4->getBody();
      $string4 = $body4->getContents();
      $json4 = json_decode($string4);

      $client5 = new GuzzleHttp\Client();
      $res5 = $client5->request('GET', 'http://localhost/shedulerapi/controller/professor.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]]
      ]
      );

      $body5 = $res5->getBody();
      $string5 = $body5->getContents();
      $json5 = json_decode($string5);

  headernav();

        ?>

    <center><h1>SIGN UP</h1></center>

       <form action="newschedulerform.php" method="post">

         <div class='form-group'>
             <label>Rooms available: </label>
             <select name='id_room_avail'>
               <?php for ($x = 0; $x < $json->data->rows_returned; $x++) { ?>
               <option value="<?php print_r($json->data->rooms_avail[$x]->id); ?>">
                 <?php
                      //print_r($json->data->rooms_avail[$x]->id);
                      echo " Lektiko Room: ";
                      print_r($room_array[$x]->data->rooms[0]->lektiko_room);
                      echo " Start Time: ";
                      print_r($timeslot_array[$x]->data->timeslots[0]->start_time);
                      echo " Day: ";
                      print_r($timeslot_array[$x]->data->timeslots[0]->day);
                      echo "  ";
                       ?>
               </option>
             <?php } ?>
             </select>
           </div>

           <div class='form-group'>
               <label>id_course</label>
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
                 <label>id_prof</label>
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
