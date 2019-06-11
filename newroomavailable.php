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
    $res = $client->request('GET', 'http://localhost/shedulerapi/controller/room.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );



    $body = $res->getBody();
    $string = $body->getContents();
    $json = json_decode($string);

    $client2 = new GuzzleHttp\Client();
    $res2 = $client2->request('GET', 'http://localhost/shedulerapi/controller/timeslot.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );



    $body2 = $res2->getBody();
    $string2 = $body2->getContents();
    $json2 = json_decode($string2);

    $client3 = new GuzzleHttp\Client();
    $res3 = $client3->request('GET', 'http://localhost/shedulerapi/controller/acad_sem.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );



    $body3 = $res3->getBody();
    $string3 = $body3->getContents();
    $json3 = json_decode($string3);

    headernav();

    ?>


    <center><h1>SIGN UP</h1></center>

       <form action="newroomavailableform.php" method="post">

               <div class='form-group'>
                   <label>ROOM</label>
                   <select name='id_room'>
                     <?php for ($x = 0; $x <= $json->data->rows_returned; $x++) { ?>
                     <option value="<?php print_r($json->data->rooms[$x]->id); ?>">
                       <?php print_r($json->data->rooms[$x]->lektiko_room); ?>
                     </option>
                   <?php } ?>
                   </select>
                 </div>

               <div class='form-group'>
                   <label>id_ts</label>
                   <select name='id_ts'>
                     <?php for ($x = 0; $x <= $json2->data->rows_returned; $x++) { ?>
                     <option value="<?php print_r($json2->data->timeslots[$x]->id); ?>">
                       <?php print_r($json2->data->timeslots[$x]->start_time); echo " ";print_r($json2->data->timeslots[$x]->day); ?>
                     </option>
                   <?php } ?>
                   </select>
                 </div>

               <div class='form-group'>
                   <label>id_acadsem</label>
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

    <?php footernav(); ?>
