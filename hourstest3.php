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
      use GuzzleHttp\Exception\ClientException;
      use GuzzleHttp\Pool;
      use GuzzleHttp\Psr7\Request;
      use GuzzleHttp\Psr7\Response;
      use Psr\Http\Message\ResponseInterface;

      use GuzzleHttp\Promise;
      try {

      (empty($_POST['username'])  ? $username = NULL : $username = $_POST['username']);
      (empty($_POST['password'])  ? $password = NULL : $password = $_POST['password']);

      $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost/shedulerapi/controller/']);
      $tokenheader = ['json' => [ 'username' => $username, 'password' => $password]] ;
      if(!isset($_SESSION["authtoken"])) {

      $res = $client->request('POST', 'sessions.php', $tokenheader);


        $json = json_decode($res->getBody()->getContents());
        $token = $json->data->access_token;
        $sessid = $json->data->session_id;
        $_SESSION["authtoken"]=$token;
        $_SESSION["sessionid"]=$sessid;
      }

      headernav();

      $nameid = $_POST['id_course'];
      $pieces = explode(",", $nameid);

      if(!empty($_POST['id_room_avail']) || !empty($_POST['id_course']) || !empty($_POST['type_division']) || !empty($_POST['id_prof'])){

      (empty($_POST['id_room_avail'])  ? $id_room_avail = NULL : $id_room_avail = $_POST['id_room_avail']);
      (empty($_POST['id_course'])  ? $id_course = NULL : $id_course = $_POST['id_course']);
      (empty($_POST['type_division'])  ? $type_division = NULL : $type_division = $_POST['type_division']);
      (empty($_POST['id_prof'])  ? $id_prof = NULL : $id_prof = $_POST['id_prof']);
      (empty($_POST['division_str'])  ? $division_str = NULL : $division_str = $_POST['division_str']);


      //echo $pieces[0] . "<br>"; // piece1
      //echo $pieces[1];
      //echo $id_prof;

      $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];

      //if(count($_POST['testtableradio'])<2){
      //  echo "ssssssssssssssssssssssssss";
      //}
      $incre = 'a';

      foreach($_POST['testtableradio'] as $option_num => $option_val){
      //echo $option_num." ".$option_val."<br>";
      $room_avail_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id_acadsem' => $_SESSION["id_acadsem"],'id' => $option_val ]];


      $res = $client->request('GET', 'room_avail.php', $room_avail_header);
      $json = json_decode($res->getBody()->getContents());
      $room_avail_array = $json->data->rooms_avail;
      $room_avail_rows = $json->data->rows_returned;
      $room_id = $room_avail_array[0]->id_room;
      $ts_id = $room_avail_array[0]->id_ts;

      $res = $client->request('POST', 'scheduler.php',
      ['headers' => ['Authorization' => $_SESSION["authtoken"]],
      'json' =>
      [
      'id_course' => $pieces[0],
      'id_acadsem' => $_SESSION["id_acadsem"],
      'type_division' => $pieces[1],
      'lektiko_division' => "tha doume pws",
      'id_prof' => $id_prof,
      'id_room' => $room_id,
      'id_ts' => $ts_id,
      'division_str' => $pieces[2],
      'learn_sem' => $_SESSION["learn_sem"]
      ]
      ]);

      $body = $res->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $postedid = $json->data->schedulers[0]->id_course;
      $postedtype = $json->data->schedulers[0]->type_division;
      //echo "skereeeeeeeeeeeeeeeeeeeeeeeeeeeeee" . $postedid;
      $incre++;

        if($type_division === "theory"){

          $res = $client->request('PATCH', 'room_avail.php',
          [
            'headers' => ['Authorization' => $_SESSION["authtoken"]],
            'query' => ['id_ts' => $ts_id],
            'json' =>  ['available' => 'N']
          ]);

          $json = json_decode($res->getBody()->getContents());
        }


        else{

          $res = $client->request('PATCH', 'room_avail.php',
        [
          'headers' => ['Authorization' => $_SESSION["authtoken"]],
          'query' => ['id' => $option_val],
          'json' =>  ['available' => 'N']
        ]
        );
        $json = json_decode($res->getBody()->getContents());

        }
        }
      }



      $weekdb = array("de", "tr", "te", "pe", "pa");
      $weekgk = array("Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή");

      $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];
      $header_authtoken = ['Authorization' => $_SESSION["authtoken"]];

      // Initiate each request but do not block
      $promises = [
          'course' => $client->getAsync('course_this_year.php',['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['learn_sem' => $_SESSION["learn_sem"], 'acad_sem' => $_SESSION["id_acadsem"]]]),
          'room_avail'  => $client->getAsync('room_avail.php', ['headers' => $header_authtoken,'query' => ['id_acadsem' => $_SESSION["id_acadsem"],'available' => 'Y', 'learn_sem' => $_SESSION["learn_sem"]]]),
          'scheduler'  => $client->getAsync('scheduler.php', ['headers' => $header_authtoken,'query' => ['id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => $_SESSION["learn_sem"]]]),
          'course_list'  => $client->getAsync('course.php', $header),

          'professor'  => $client->getAsync('professor.php', $header)
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
      //echo "<br> id room: " . $room_avail_array[0]->id_room;

      //print_r($room_avail_array);
      //echo $room_avail_rows;
      //echo $json->data->rows_returned;

      //print_r(json_decode($results['scheduler']['value']->getBody(), true));


      $body = $results['scheduler']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $scheduler_array = $json->data->schedulers;
      $scheduler_rows = $json->data->rows_returned;
      $scheduler_type = $scheduler_array[0]->type_division;

      $body = $results['professor']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $professor_array = $json->data->professors;
      $professor_rows = $json->data->rows_returned;

      $body = $results['course_list']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $courses_array = $json->data->courses;
      $courses_rows = $json->data->rows_returned;

      echo "77" . $courses_array[77]->hours_lab;


      $body = $results['course']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $course_list_array = $json->data->coursethisyears;
      $course_rows = $json->data->rows_returned;

            //$course_lab_div = $json->data->coursethisyears->count_div_lab;

      for($x=0 ; $x<$course_rows ; $x++){
        $promises = [
            'getdivision'  => $client->getAsync('scheduler.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]], 'query' => ['id_course' => $course_list_array[$x]->id_course, 'id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => "A"]])
            ];

        // Wait on all of the requests to complete. Throws a ConnectException
        // if any of the requests fail
        $results = Promise\unwrap($promises);

        // Wait for the requests to complete, even if some of them fail
        $results = Promise\settle($promises)->wait();

        $body = $results['getdivision']['value']->getBody();
        $string = $body->getContents();
        $json = json_decode($string);
        $getdivision_array = $json->data->schedulers;
        $getdivision_rows = $json->data->rows_returned;

        echo "<br> getdivision_rows" . $getdivision_rows;


      }

      //print_r($scheduler_array);
      //echo $scheduler_rows;



      for ($x = 0; $x < $room_avail_rows; $x++) {
        $promises = [
            'room_1'   => $client->getAsync('room.php', ['headers' => $header_authtoken,'query' => ['id' => $room_avail_array[$x]->id_room ]]),
            'timeslot_1'  => $client->getAsync('timeslot.php', ['headers' => $header_authtoken  ,'query' =>['id'=>$room_avail_array[$x]->id_ts ]])
        ];

        $results = Promise\unwrap($promises);

        // Wait for the requests to complete, even if some of them fail
        $results = Promise\settle($promises)->wait();


        $body = $results['room_1']['value']->getBody();
        $string = $body->getContents();
        $room_array[$x] = json_decode($string);
        //print_r($room_array);

        $body = $results['timeslot_1']['value']->getBody();
        $string = $body->getContents();
        $timeslot_array[$x] = json_decode($string);
      }


      for ($x = 0; $x < $scheduler_rows; $x++) {
        $promises = [
            'room_2'   => $client->getAsync('room.php', ['headers' => $header_authtoken,'query' => ['id' => $scheduler_array[$x]->id_room ]]),
            'timeslot_2'  => $client->getAsync('timeslot.php', ['headers' => $header_authtoken,'query' =>['id'=>$scheduler_array[$x]->id_ts ]]),
            'course_2'  => $client->getAsync('course_this_year.php', ['headers' => $header_authtoken,'query' => ['id_course' => $scheduler_array[$x]->id_course]])
        ];

        $results = Promise\unwrap($promises);

        // Wait for the requests to complete, even if some of them fail
        $results = Promise\settle($promises)->wait();

        // You can access each result using the key provided to the unwrap
        // function.


        $body = $results['room_2']['value']->getBody();
        $string = $body->getContents();
        $room_array_sch[$x] = json_decode($string);
        //print_r($room_array_sch);

        $body = $results['timeslot_2']['value']->getBody();
        $string = $body->getContents();
        $timeslot_array_sch[$x] = json_decode($string);
        //print_r($timeslot_array_sch);

        $body = $results['course_2']['value']->getBody();
        $string = $body->getContents();
        $course_array[$x] = json_decode($string);

      }

      if($scheduler_rows > $room_avail_rows){
        $loop = $scheduler_rows;
      }
      else {
        $loop = $room_avail_rows;
      }
      //echo "course rows" . $courses_rows;
      //echo "ela" . $courses_array[77]->course_id;

      ?>

      <form action="newscheduleraio_NO_ASYNC.php" method="post">


      <div class="sidenav">

        <div class='form-group'>
            <label>Καθηγητής</label>
            <select name='id_prof'>
              <?php for ($x = 0; $x < $professor_rows; $x++) { ?>
              <option value="<?php print_r($professor_array[$x]->id); ?>">
                <?php print_r($professor_array[$x]->fullname); ?>
              </option>
            <?php } ?>
            </select>
          </div>

        <div class='form-group'>
            <label>Course</label>
            <div class="form-group">
             <select multiple class="form-control tallform" id="exampleFormControlSelect2" name='id_course'>
                 <?php for ($x = 0; $x < $course_rows; $x++) {
                   $course_lab_count = $course_list_array[$x]->count_div_lab;
                   $count_lab[$pieces[2]] = 1;
                   //$test[$pieces[2]] = 15555;
                   //echo "spera" . $test[$pieces[2]];

                   for ($z = 0; $z < $courses_rows; $z++) {
                     if($courses_array[$z]->course_id === $course_list_array[$x]->id_course){
                       $lab_hours[$pieces[2]] = $courses_array[$z]->hours_lab;
                     }
                     }

                     for ($z = 0; $z < $getdivision_rows; $z++) {
                     if($course_list_array[$x]->id_course === $getdivision_array[$z]->id_course){
                       if ($getdivision_array[$z]->type_division === "LAB"){
                         if ($lab_hours[$pieces[2]] == $getdivision_rows){
                         $course_lab_count = $course_list_array[$x]->count_div_lab - $count_lab[$pieces[2]];
                         $count_lab[$pieces[2]]++;
                       }
                       elseif ($lab_hours > $getdivision_rows){
                         $lab_hours[$pieces[2]] = $lab_hours[$pieces[2]] - $getdivision_rows;

                       }
                     }
                   }
                   echo "<br> pieces : " . $pieces[2];
                   echo "<br> countlab: " . $count_lab[$pieces[2]];
                   echo "<br> count div lab: " . $course_list_array[$x]->count_div_lab;
                 }
                   if ($course_list_array[$x]->count_div_lab > 0) {
                   for ($y = 1; $y <= $course_lab_count; $y++){?>

                   <option value="<?php $lab = "LAB"; echo $course_list_array[$x]->id_course . "," . $lab . "," . $y . "/" . "LAB" . "/" . $course_list_array[$x]->id_course; ?>">

                     <?php
                       echo "Lab Division " . $y . " ";
                       echo " hours left: " . $lab_hours[$pieces[2]];
                       echo " ". $course_list_array[$x]->name . "<br>";
                       ?>
                       </option>
                       <?php
                     }
                   }
                   ?>

                   <?php
                   $course_theory_count = $course_list_array[$x]->count_div_theory;
                   $count_theory = 1;

                   for ($z = 0; $z < $getdivision_rows; $z++) {
                     if($course_list_array[$x]->id_course === $getdivision_array[$z]->id_course){
                       if ($getdivision_array[$z]->type_division === "THEORY"){
                         $course_theory_count = $course_list_array[$x]->count_div_theory - $count_theory;
                         $count_theory++;
                       }
                     }
                   }
                   if ($course_list_array[$x]->count_div_theory > 0) {
                   for ($y = 1; $y <= $course_theory_count; $y++){?>

                   <option value="<?php $theory = "THEORY"; echo $course_list_array[$x]->id_course . "," . $theory; ?>">

                     <?php
                       echo "Theory Division " . $y . " ";
                       echo $course_list_array[$x]->name . "<br>";
                       ?>
                       </option>
                       <?php
                     }
                   }
                   ?>

                   <?php
                   $course_practice_count = $course_list_array[$x]->count_div_practice;
                   $count_practice = 1;

                   for ($z = 0; $z < $getdivision_rows; $z++) {
                     if($course_list_array[$x]->id_course === $getdivision_array[$z]->id_course){
                       if ($getdivision_array[$z]->type_division === "PRACTICE"){
                         $course_practice_count = $course_list_array[$x]->count_div_practice - $count_practice;
                         $count_practice++;
                       }
                     }
                   }
                   if ($course_list_array[$x]->count_div_practice > 0) {
                   for ($y = 1; $y <= $course_practice_count; $y++){?>

                   <option value="<?php $practice = "PRACTICE"; echo $course_list_array[$x]->id_course . "," . $practice; ?>">

                     <?php
                       echo "Practice Division " . $y . " ";
                       echo $course_list_array[$x]->name . "<br>";
                       ?>
                       </option>
                       <?php
                     }
                   }
                   ?>

               <?php } ?>
             </select>
           </div>

          </div>
      </div>

      <div class="main">

      <pre> <?php //print_r($json->data->rooms[0]->id);
       ?> </pre>


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
              //echo "==========<br>";
              //echo "<br>str" . $x . ": ". $scheduler_array[$x]->division_str . "<br>";
              //echo "start time: " . $timeslot_array_sch[$x]->data->timeslots[0]->start_time . "<->" . $st . "<br>";
              //echo "week day: " . $timeslot_array[$x]->data->timeslots[0]->day . "<->" . $weekdb[$y] . "<br>";
              //echo "==========";
              if($timeslot_array[$x]->data->timeslots[0]->start_time == $st && $weekdb[$y] === $timeslot_array[$x]->data->timeslots[0]->day) {
                //echo $timeslot_array->data->timeslots[$x]->start_time . ":00";
                //echo str_replace($weekdb[$y], $weekgk[$y], $timeslot_array->data->timeslots[$x]->day);
                ?><div class="form-check">
                <input class="form-check-input" type="radio" name="testtableradio[<?php echo $x;?>]" value="<?php echo $room_avail_array[$x]->id; ?>" >
                <?php echo $room_array[$x]->data->rooms[0]->lektiko_room; ?>
              </div>
                <?php
              }
                elseif($timeslot_array_sch[$x]->data->timeslots[0]->start_time == $st && $weekdb[$y] === $timeslot_array_sch[$x]->data->timeslots[0]->day) {
                  //echo str_replace($weekdb[$y], $weekgk[$y], $timeslot_array[$x]->data->timeslots[0]->day);
                  echo $room_array_sch[$x]->data->rooms[0]->lektiko_room . "<br>";
                  echo $course_array[$x]->data->coursethisyears[0]->name . "<br>";
                  echo $scheduler_array[$x]->type_division . "<br>";
                  echo $scheduler_array[$x]->division_str . "<br>";
                }
            }
            ?>
            </td>
            <?php } ?>
          </tr>
        <?php } ?>

        </tbody>
      </table>
      <?php if($room_avail_rows > 0) {?>










           <br><input type="submit" value="Submit">
<?php } ?>

          </form>

        </div>

        <?php


        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = (string) $response->getBody();
            $json = json_decode($responseBodyAsString);
            $responsestatuscode = $response->getStatusCode();
            $messages = $json->messages;
        }


     ?>


      <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>







       <?php footernav(); ?>
