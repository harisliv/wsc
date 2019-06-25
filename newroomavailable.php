    <?php include "header.php"; ?>
    <?php include "footer.php"; ?>
    <?php session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    error_reporting(0);

    $weekdb = array("de", "tr", "te", "pe", "pa");
    $weekgk = array("Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή");

    require "vendor/autoload.php";
    use GuzzleHttp\Client;
    headernav();

    try {

    //echo $_SESSION["id_acadsem"];

    $client = new GuzzleHttp\Client();


    $res = $client->request('GET', 'http://localhost/shedulerapi/controller/room.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );

    $response = (string) $res->getBody();
    $json = json_decode($response);
    $messages = $json->messages;

    $client2 = new GuzzleHttp\Client();
    $res2 = $client2->request('GET', 'http://localhost/shedulerapi/controller/timeslot.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]],
    'query' => ['id_acadsem' => $_SESSION["id_acadsem"]]
    ]
    );

    $response2 = (string) $res2->getBody();
    $json2 = json_decode($response2);
    $messages = $json2->messages;
    ?>
    <center><h1>New Room Availability</h1></center>

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
<?php      ?>
            <div class='form-group'>
                <label>Time and Day by acadsem</label>
                <select name='id_ts'>
                  <?php for ($x = 0; $x < $json2->data->rows_returned; $x++) { ?>
                  <option value="<?php print_r($json2->data->timeslots[$x]->id); ?>">
                    <?php echo $json2->data->timeslots[$x]->start_time . ":00 ";
                    for ($y = 0; $y <= 4; $y++) {
                    if($weekdb[$y] === $json2->data->timeslots[$x]->day){
                      echo str_replace($weekdb[$y], $weekgk[$y], $json2->data->timeslots[$x]->day);
                    }} ?>
                  </option>
                <?php } ?>
                </select>
              </div>

              <input type="submit" value="Submit">

         </form>

       <?php }



       catch (GuzzleHttp\Exception\BadResponseException $e) {
           $response = $e->getResponse();
           $responseBodyAsString = (string) $response->getBody();
           $json = json_decode($responseBodyAsString);
           $responsestatuscode = $response->getStatusCode();
           $messages = $json->messages;
       }


    ?>


    <center><h1><?php foreach($messages as $value) { echo $value . "<br>"; } ?></h1></center>



    <?php footernav(); ?>
