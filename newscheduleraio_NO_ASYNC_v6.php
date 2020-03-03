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
      if (isset($_POST["form1"])){


      if (isset($_POST['delete']) && isset($_POST['testtableradio'])){
        ?><div class="alert alert-danger" role="alert"><?php
        echo "Η ΕΝΕΡΓΙΑ ΠΟΥ ΕΠΙΛΕΞΑΤΕ ΔΕΝ ΕΙΝΑΙ ΕΦΙΚΤΗ";
        ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div><?php
      }
      //ΝΟ 1
      else{
        if($_SESSION["refresh_post"] === $_POST['testtableradio'] || $_SESSION["refresh_delete"] === $_POST['delete']){
          ?><div class="alert alert-danger" role="alert"><?php
          echo "ΔΕΝ ΓΙΝΕΤΑΙ ΝΑ ΚΑΝΟΥΜΕ SUBMIT ΤΑ ΙΔΙΑ VALUES ME REFRESH";
          ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div><?php
        }
        //ΝΟ 2
        else{

        //DELETE IF
        if (isset($_POST['delete'])) {

        $_SESSION["refresh_delete"] = $_POST['delete'];
        foreach($_POST['delete'] as $del_num => $del_val){

        $pieces_delete = explode(",", $del_val);
        //print_r($pieces_delete);

        $res = $client->request('GET', 'room_avail.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => [
          'id_room' => $pieces_delete[0],
          'id_ts' => $pieces_delete[1],
          'id_acadsem' => $_SESSION["id_acadsem"]
          ]]);

        $json = json_decode($res->getBody()->getContents());

        $room_avail_del_id = $json->data->rooms_avail[0]->id;


        $res = $client->request('PATCH', 'room_avail.php',
      [
        'headers' => ['Authorization' => $_SESSION["authtoken"]],
        'query' => ['id' => $room_avail_del_id],
        'json' =>  ['available' => 'Y']
      ]
      );

      $res = $client->request('DELETE', 'scheduler.php',
      [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['id_room' => $pieces_delete[0], 'id_ts' => $pieces_delete[1], 'id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => $_SESSION["learn_sem"]]
      ]
      );

      $json = json_decode($res->getBody()->getContents());


      }
    }

    //elseif (isset($_POST['delete']) && !isset($_POST['deletebox'])) {}
    //SUBMIT IF
    elseif (isset($_POST['testtableradio']) && isset($_POST['id_prof']) && isset($_POST['id_course'])) {





      $pieces = explode(",", $_POST['id_course']);
      $pieces_prof = explode(",", $_POST['id_prof']);

      $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];


      $promises = [
          'getonediv'  => $client->getAsync('scheduler.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]], 'query' => ['id_acadsem' => $_SESSION["id_acadsem"], 'division_str' => $pieces[2]]])
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
      $arranged_one = $json->data->rows_returned;

      $checked_arr = $_POST['testtableradio'];
      $count_checked = count($checked_arr);

      if($pieces[3] - $arranged_one < $count_checked){
        ?><div class="alert alert-danger" role="alert"><?php
        echo "ΕΧΕΤΕ ΕΠΙΛΕΞΕΙ ΠΕΡΙΣΣΟΤΕΡΕΣ ΩΡΕΣ ΑΠΟ ΑΥΤΕΣ ΠΟΥ ΕΙΝΑΙ ΔΙΑΘΕΣΙΜΕΣ";
        ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div><?php

      }

      else{

      $_SESSION["refresh_post"] = $_POST['testtableradio'];

      foreach($_POST['testtableradio'] as $option_num => $option_val){
      //echo $option_num." ".$option_val."<br>";
      //$room_avail_header = ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => ['id_acadsem' => $_SESSION["id_acadsem"],'id' => $option_val ]];
      $pieces_room_avail = explode(",", $option_val);

      $res = $client->request('GET', 'room_avail.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]],'query' => [
        'id_room' => $pieces_room_avail[0],
        'id_ts' => $pieces_room_avail[1],
        'id_acadsem' => $_SESSION["id_acadsem"]]]);

      $json = json_decode($res->getBody()->getContents());
      $room_avail_array = $json->data->rooms_avail;
      $room_avail_rows = $json->data->rows_returned;
      $room_id = $room_avail_array[0]->id_room;
      $room_avail_id = $room_avail_array[0]->id;
      $ts_id = $room_avail_array[0]->id_ts;

      $res = $client->request('GET', 'scheduler.php',
    [
      'headers' => ['Authorization' => $_SESSION["authtoken"]],
      'query' => ['id_ts' => $ts_id, 'id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => $_SESSION["learn_sem"]]
    ]
    );

    $json = json_decode($res->getBody()->getContents());
    $scheduler_rows_del = $json->data->rows_returned;
    //echo "scheduler_rows_del :" . $scheduler_rows_del;

        if($pieces[1] === "THEORY" && $scheduler_rows_del > 0){

        ?><div class="alert alert-danger" role="alert"><?php
        echo "ΣΕ ΕΝΑ ΔΙΔΑΚΤΙΚΟ ΕΞΑΜΗΝΟ ΟΙ ΘΕΩΡΙΕΣ ΔΕΝ ΠΡΕΠΕΙ ΝΑ ΔΙΔΑΣΚΟΝΤΑΙ ΤΑΥΤΟΧΡΟΝΑ ΜΕ ΚΑΠΟΙΟ ΕΡΓΑΣΤΗΡΙΟ";
        ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div><?php

        }


        else{

          $res = $client->request('GET', 'scheduler.php',
        [
          'headers' => ['Authorization' => $_SESSION["authtoken"]],
          'query' => ['id_ts' => $ts_id, 'id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => $_SESSION["learn_sem"]]
        ]
        );

        $json = json_decode($res->getBody()->getContents());
        $scheduler_check_type = $json->data->schedulers[0]->type_division;

        if($scheduler_check_type == "THEORY"){
          ?><div class="alert alert-danger" role="alert"><?php
          echo "ΣΕ ΕΝΑ ΔΙΔΑΚΤΙΚΟ ΕΞΑΜΗΝΟ ΟΙ ΘΕΩΡΙΕΣ ΔΕΝ ΠΡΕΠΕΙ ΝΑ ΔΙΔΑΣΚΟΝΤΑΙ ΤΑΥΤΟΧΡΟΜΑ ΜΕ ΚΑΠΟΙΟ ΕΡΓΑΣΤΗΡΙΟ";
          ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button></div><?php
  }
  else{

          $res = $client->request('PATCH', 'room_avail.php',
        [
          'headers' => ['Authorization' => $_SESSION["authtoken"]],
          'query' => ['id' => $room_avail_id],
          'json' =>  ['available' => 'N']
        ]
        );
        $json = json_decode($res->getBody()->getContents());

        $res = $client->request('POST', 'scheduler.php',
        ['headers' => ['Authorization' => $_SESSION["authtoken"]],
        'json' =>
        [
        'id_course' => $pieces[0],
        'id_acadsem' => $_SESSION["id_acadsem"],
        'type_division' => $pieces[1],
        'lektiko_division' => $pieces_room_avail[2] . "/" . $pieces_prof[1],
        'id_prof' => $pieces_prof[0],
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
      }
          }

          }
        }

    }

    else {
      ?><div class="alert alert-danger" role="alert"><?php
      echo "ΔΕΝ ΕΧΕΤΕ ΣΥΜΠΛΗΡΩΣΕΙ ΤΑ ΑΠΑΡΑΙΤΗΤΑ ΣΤΟΙΧΕΙΑ";
      ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button></div><?php
    }
  }
}
}
elseif(!isset($_POST["form1"])){
  $_SESSION['refresh_post'] = 'a';
  $_SESSION['refresh_delete'] = 'a';
}


      $weekdb = array("de", "tr", "te", "pe", "pa");
      $weekgk = array("Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή");

      $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];
      $header_authtoken = ['Authorization' => $_SESSION["authtoken"]];

      // Initiate each request but do not block
      $promises = [
          'course' => $client->getAsync('course_this_year.php',['headers' => $header_authtoken,'query' => ['learn_sem' => $_SESSION["learn_sem"], 'id_acadsem' => $_SESSION["id_acadsem"]]]),
          'room_avail'  => $client->getAsync('room_avail.php', ['headers' => $header_authtoken,'query' => ['id_acadsem' => $_SESSION["id_acadsem"]]]),
          'scheduler'  => $client->getAsync('scheduler.php', ['headers' => $header_authtoken,'query' => ['id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => $_SESSION["learn_sem"]]]),
          'course_list'  => $client->getAsync('course.php', $header),
          'room_list'   => $client->getAsync('room.php', $header),
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

      $body = $results['scheduler']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $scheduler_array = $json->data->schedulers;
      $scheduler_rows = $json->data->rows_returned;

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

      $body = $results['room_list']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $room_list_array = $json->data->rooms;
      $room_list_rows = $json->data->rows_returned;

      $body = $results['course']['value']->getBody();
      $string = $body->getContents();
      $json = json_decode($string);
      $course_list_array = $json->data->coursethisyears;
      $course_rows = $json->data->rows_returned;


      ?>

      <form action="newscheduleraio_NO_ASYNC_v6.php" method="post">


      <div class="sidenav">



        <div class='form-group'>
            <label style="color:white;">ΕΠΙΛΟΓΗ ΜΑΘΗΜΑΤΟΣ</label>
            <div class="form-group">
             <select multiple class="form-control tallform" id="exampleFormControlSelect2" name='id_course'>
                 <?php for ($x = 0; $x < $course_rows; $x++) {

                   if ($course_list_array[$x]->count_div_lab > 0) {
                   for ($y = 1; $y <= $course_list_array[$x]->count_div_lab; $y++){

                     $promises = [
                         'getdiv'  => $client->getAsync('scheduler.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]],
                         'query' =>
                         [
                           'id_acadsem' => $_SESSION["id_acadsem"],
                           'division_str' => $y . "/" . "Ε" . "/" . $course_list_array[$x]->name]])
                         ];

                     // Wait on all of the requests to complete. Throws a ConnectException
                     // if any of the requests fail
                     $results = Promise\unwrap($promises);

                     // Wait for the requests to complete, even if some of them fail
                     $results = Promise\settle($promises)->wait();

                     $body = $results['getdiv']['value']->getBody();
                     $string = $body->getContents();
                     $json = json_decode($string);
                     //$getdivision_array = $json->data->schedulers;
                     $arranged = $json->data->rows_returned;

                     for ($z = 0; $z < $courses_rows; $z++) {
                       if($courses_array[$z]->course_id === $course_list_array[$x]->id_course){
                         $lab_hours = $courses_array[$z]->hours_lab;
                       }
                       }

                     $hoursleft = $lab_hours - $arranged;

                     if($hoursleft == 0 ){ ?>

                       <option disabled>

                         <?php

                         //echo $y . ") Lab Division:";
                         echo " ". $course_list_array[$x]->name . " [ΕΡΓ. " . $y . "]<br>";

                         ?>
                         </option>

                         <?php
                       }
                     else{ ?>

                     <option class="lab" value="<?php echo $course_list_array[$x]->id_course . "," . "LAB" . "," . $y . "/" . "Ε" . "/" . $course_list_array[$x]->name . "," . $lab_hours; ?>">

                       <?php

                       echo $course_list_array[$x]->name . " [ΕΡΓ-" . $y . "] ΩΡΕΣ:" . $hoursleft . "<br>";


                         ?>
                         </option>
                         <?php }
                      }
                   }
                   ?>

                   <?php
//######################################################################################
                   //THEORY
//######################################################################################

                     if ($course_list_array[$x]->count_div_theory > 0) {
                     for ($y = 1; $y <= $course_list_array[$x]->count_div_theory; $y++){

                       $promises = [
                           'getdiv'  => $client->getAsync('scheduler.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]],
                           'query' =>
                           [
                             'id_acadsem' => $_SESSION["id_acadsem"],
                             'division_str' => $y . "/" . "Θ" . "/" . $course_list_array[$x]->name
                             ]
                           ])];

                       // Wait on all of the requests to complete. Throws a ConnectException
                       // if any of the requests fail
                       $results = Promise\unwrap($promises);

                       // Wait for the requests to complete, even if some of them fail
                       $results = Promise\settle($promises)->wait();

                       $body = $results['getdiv']['value']->getBody();
                       $string = $body->getContents();
                       $json = json_decode($string);
                       //$getdivision_array = $json->data->schedulers;
                       $arranged = $json->data->rows_returned;

                       for ($z = 0; $z < $courses_rows; $z++) {
                         if($courses_array[$z]->course_id === $course_list_array[$x]->id_course){
                           $theory_hours = $courses_array[$z]->hours_theory;
                         }
                         }

                       $hoursleft = $theory_hours - $arranged;

                       if($hoursleft == 0 ){ ?>

                         <option value="<?php echo $course_list_array[$x]->id_course . "," . "THEORY" . "," . $y . "/" . "THEORY" . "/" . $course_list_array[$x]->name; ?>" disabled>

                           <?php

                           echo " ". $course_list_array[$x]->name . " [ΘΕΩ. " . $y . "]<br>";


                           ?>
                           </option>

                           <?php
                         }
                       else{ ?>

                       <option class="theory" name="theory" value="<?php echo $course_list_array[$x]->id_course . "," . "THEORY" . "," . $y . "/" . "Θ" . "/" . $course_list_array[$x]->name . "," . $theory_hours; ?>">

                         <?php

                         echo $course_list_array[$x]->name . " [ΘΕΩ-" . $y . "] ΩΡΕΣ:" . $hoursleft . "<br>";


                           ?>
                           </option>
                           <?php }
                        }
                     }
                   ?>

                   <?php
//######################################################################################
                   //PRACTICE
//######################################################################################

                     if ($course_list_array[$x]->count_div_practice > 0) {
                     for ($y = 1; $y <= $course_list_array[$x]->count_div_practice; $y++){

                       $promises = [
                           'getdiv'  => $client->getAsync('scheduler.php', ['headers' => ['Authorization' => $_SESSION["authtoken"]],
                           'query' =>
                           [
                             'id_acadsem' => $_SESSION["id_acadsem"],
                             'division_str' => $y . "/" . "ΑΠ" . "/" . $course_list_array[$x]->name
                           ]
                           ])];

                       // Wait on all of the requests to complete. Throws a ConnectException
                       // if any of the requests fail
                       $results = Promise\unwrap($promises);

                       // Wait for the requests to complete, even if some of them fail
                       $results = Promise\settle($promises)->wait();

                       $body = $results['getdiv']['value']->getBody();
                       $string = $body->getContents();
                       $json = json_decode($string);
                       //$getdivision_array = $json->data->schedulers;
                       $arranged = $json->data->rows_returned;

                       for ($z = 0; $z < $courses_rows; $z++) {
                         if($courses_array[$z]->course_id === $course_list_array[$x]->id_course){
                           $practice_hours = $courses_array[$z]->hours_practice;
                         }
                         }

                       $hoursleft = $practice_hours - $arranged;

                       if($hoursleft == 0 ){ ?>

                         <option value="<?php echo $course_list_array[$x]->id_course . "," . "PRACTICE" . "," . $y . "/" . "PRACTICE" . "/" . $course_list_array[$x]->name; ?>" disabled>

                           <?php

                           echo " ". $course_list_array[$x]->name . " [ΠΡΑΚ. " . $y . "]<br>";


                           ?>
                           </option>

                           <?php
                         }
                       else{ ?>

                       <option class="practice" value="<?php echo $course_list_array[$x]->id_course . "," . "PRACTICE" . "," . $y . "/" . "ΑΠ" . "/" . $course_list_array[$x]->name . "," . $practice_hours; ?>">

                         <?php

                         echo $course_list_array[$x]->name . " [ΠΡΑΚ-" . $y . "] ΩΡΕΣ:" . $hoursleft . "<br>";


                           ?>
                           </option>
                           <?php }
                        }
                      }

                   ?>

               <?php } ?>
             </select>
           </div>

         </div>

         <div class='form-group'>
             <select name='id_prof' class="form-control">
               <option value="-" disabled selected>
                 <?php echo "ΕΠΙΛΕΞΤΕ ΔΙΔΑΣΚΩΝ ΚΑΘΗΓΗΤΗ"; ?>
               </option>
               <?php for ($x = 0; $x < $professor_rows; $x++) { ?>
               <option value="<?php echo $professor_array[$x]->id . "," . $professor_array[$x]->fullname; ?>">
                 <?php echo $professor_array[$x]->fullname; ?>
               </option>
             <?php } ?>
             </select>
           </div>


          <?php if($room_avail_rows > 0) {?> <center><input class="btn btn-primary btn-lg btn-block" onclick="return postConfirm()" name="form1" type="submit" value="ΥΠΟΒΟΛΗ" id="submit"></center><br> <?php } ?>

      </div>

      <div class="main">
        <h1 style="text-align:center;color:#212529;">Πρόγραμμα</h1><br>
      <table class="table table-bordered">
        <thead class="thead-dark">
          <th> </th>
           <?php for ($y = 0; $y <= 4; $y++) { ?>
          <th>
            <?php echo "<center>" . $weekgk[$y] . "</center>"; ?>
          </th>
          <?php } ?>
        </thead>
        <tbody>

          <?php for ($st = 1 ; $st <= 13 ; $st++) { ?>
          <tr>
            <td bgcolor="#212529" style="color:white;"><?php $time = $st + 7; echo "<strong>" . $time . ":00</strong>"; ?></td>
            <?php for ($x = 0; $x <= 4; $x++) { ?>
            <td align="center">

              <?php
              $id_ts = $st + $x * 13;
              //echo "id_ts:" . $id_ts . "<br>";
              for ($y = 0; $y < $room_list_rows; $y++) {

                for ($z = 0; $z < $room_avail_rows; $z++) {
                  $id_room = $y+1;
                  if($room_avail_array[$z]->id_ts == $id_ts && $room_avail_array[$z]->id_room == $id_room && $room_avail_array[$z]->available == "N"){
                    //echo $room_avail_array[$z]->id_ts . " " . $room_avail_array[$z]->id_room . ") ";
                    $res = $client->request('GET', 'scheduler.php',
                  [
                    'headers' => ['Authorization' => $_SESSION["authtoken"]],
                    'query' => ['id_room' => $id_room, 'id_ts' => $id_ts, 'id_acadsem' => $_SESSION["id_acadsem"], 'learn_sem' => $_SESSION["learn_sem"]]
                  ]
                  );
                  $json = json_decode($res->getBody()->getContents());
                  $scheduled = $json->data->schedulers;
                  $scheduled_rows = $json->data->rows_returned;
                  if($scheduled_rows > 0){
                  ?>
                  <div class="form-check harisformdelete"><label class="labelformcheck2 <?php echo $scheduled[0]->type_division ?>" data-toggle="tooltip" data-placement="top" title="Διαφραφή καταγραφής">
                  <input onclick="myFunctionDelete()" onchange="isChecked(this, 'submit')" class="form-check-input inputjsgreen" type="checkbox" name="delete[<?php echo $id_room . "" . $id_ts;?>]"
                  value="<?php echo $id_room . "," . $id_ts . "," . $scheduled[0]->type_division;?>" >
                  <?php
                  $pieces_div_echo = explode("/", $scheduled[0]->division_str);
                  $pieces_acro_title = explode(" ", $pieces_div_echo[2]);
                  $result = "";
                  foreach ($pieces_acro_title as $char){
                    $result .= mb_substr($char,0,1,'UTF-8');
                  }
                  $pieces_lektiko = explode("/", $scheduled[0]->lektiko_division);


                  echo "<center>" . $result .  "-" . $pieces_div_echo[1] . "" . $pieces_div_echo[0] . "<br>" . $pieces_lektiko[0]  . "<br>" . $pieces_lektiko[1] ."</center></label><br>";
                  ?>
                </div>
                  <?php
                }

                elseif ($scheduled_rows == 0){
                  //echo "shit<br>";
                }
                  }

                  if($room_avail_array[$z]->id_ts == $id_ts && $room_avail_array[$z]->id_room == $id_room && $room_avail_array[$z]->available == "Y"){
                    //echo "kalos" . $room_avail_array[$z]->id_ts . " " . $room_avail_array[$z]->id_room . ") ";
                    ?><div class="form-check harisformcheck"><label class="labelformcheck" data-toggle="tooltip" data-placement="top" title="Επιλογή αίθουσας">
                    <input onclick="myFunctionSubmit()" onchange="isChecked(this, 'submit')" class="form-check-input inputjsred" type="checkbox" name="testtableradio[<?php echo $id_room . "" . $id_ts;?>]" value="<?php echo $id_room . "," . $id_ts . "," . $room_list_array[$y]->room_code; ?>" >
                    <?php
                    //echo $room_list_array[$y]->id . " ";
                    echo $room_list_array[$y]->lektiko_room . "</label>"; ?>
                  </div>
                    <?php

                  }

                }

              }

              ?>

            </td>
            <?php } ?>
          </tr>
        <?php } ?>

        </tbody>
      </table>

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
