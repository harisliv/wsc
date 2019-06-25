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
    $res = $client->request('GET', 'http://localhost/shedulerapi/controller/course.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );

    $body = $res->getBody();
    $string = $body->getContents();
    $json = json_decode($string);

    $client2 = new GuzzleHttp\Client();
    $res2 = $client2->request('GET', 'http://localhost/shedulerapi/controller/professor.php',
    [
    'headers' => ['Authorization' => $_SESSION["authtoken"]]
    ]
    );

    $body2 = $res2->getBody();
    $string2 = $body2->getContents();
    $json2 = json_decode($string2);




     headernav(); ?>

  <center><h1>SIGN UP</h1></center>

     <form action="newcoursethisyearform.php" method="post">

       <div class='form-group'>
           <label>id_course</label>
           <select name='id_course'>
             <?php for ($x = 0; $x < $json->data->rows_returned; $x++) { ?>
             <option value="<?php print_r($json->data->courses[$x]->id); ?>">
               <?php print_r($json->data->courses[$x]->name); ?>
             </option>
           <?php } ?>
           </select>
         </div>

             <div class='form-group'>
                 <label>id_responsible_prof</label>
                 <select name='id_responsible_prof'>
                   <?php for ($x = 0; $x < $json2->data->rows_returned; $x++) { ?>
                   <option value="<?php print_r($json2->data->professors[$x]->id); ?>">
                     <?php print_r($json2->data->professors[$x]->fullname);  ?>
                   </option>
                 <?php } ?>
                 </select>
               </div>

             <div class='form-group'>
                 <label for='count_div_theory'>count_div_theory</label>
                 <input type='count_div_theory' class='form-control' id='count_div_theory' name='count_div_theory' placeholder='count_div_theory'>
             </div>

             <div class='form-group'>
                 <label for='count_div_lab'>count_div_lab</label>
                 <input type='count_div_lab' class='form-control' id='count_div_lab' name='count_div_lab' placeholder='count_div_lab'>
             </div>

             <div class='form-group'>
                 <label for='count_div_practice'>count_div_practice</label>
                 <input type='count_div_practice' class='form-control' id='count_div_practice' name='count_div_practice' placeholder='count_div_practice'>
             </div>

              <input type="submit" value="Submit">
          </form>

  <?php footernav(); ?>
