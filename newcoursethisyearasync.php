    <?php include "header.php"; ?>
    <?php include "footer.php"; ?>
    <?php session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //error_reporting(0);

    require "vendor/autoload.php";
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use GuzzleHttp\Pool;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Psr7\Response;

    use GuzzleHttp\Promise;

    $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost/shedulerapi/controller/']);
    $header = ['headers' => ['Authorization' => $_SESSION["authtoken"]]];
    $header_authtoken = ['Authorization' => $_SESSION["authtoken"]];


    if (!empty($_POST['id_course']) || !empty($_POST['id_responsible_prof']) || !empty($_POST['count_div_theory']) || !empty($_POST['count_div_lab']) || !empty($_POST['count_div_practice'])){

      echo "elaousai";

        $id_course = $_POST['id_course'];
        $id_responsible_prof = $_POST['id_responsible_prof'];
        //$id_acadsem = $_POST['id_acadsem'];
        (empty($_POST['count_div_theory'])  ? $count_div_theory = NULL : $count_div_theory = $_POST['count_div_theory']);
        (empty($_POST['count_div_lab'])  ? $count_div_lab = NULL : $count_div_lab = $_POST['count_div_lab']);
        (empty($_POST['count_div_practice'])  ? $count_div_practice = NULL : $count_div_practice = $_POST['count_div_practice']);


        $promise = $client->postAsync('course_this_year.php',['headers' => ['Authorization' => $_SESSION["authtoken"]],

          'json' =>
            [
        'id_course' => $id_course,
        'id_responsible_prof' => $id_responsible_prof,
        'id_acadsem' => $_SESSION["id_acadsem"],
        'count_div_theory' => $count_div_theory,
        'count_div_lab' => $count_div_lab,
        'count_div_practice' => $count_div_practice
            ]]);

            $promise->then(
              function (ResponseInterface $res) {
                  echo "kif" . $res->getStatusCode() . "\n";
                  $json = json_decode((string)$res->getBody());
                  //print_r($json);

              },
              function (RequestException $e) {
                  echo $e->getMessage() . "\n";
                  echo $e->getRequest()->getMethod();
              }
      );

      // Force the pool of requests to complete
      $promise->wait();

    }



    $promises = [
        'course' => $client->getAsync('course.php', $header),
        'professor'  => $client->getAsync('professor.php', $header)
    ];

    $results = Promise\unwrap($promises);

    // Wait for the requests to complete, even if some of them fail
    $results = Promise\settle($promises)->wait();

    // You can access each result using the key provided to the unwrap
    // function.
    //echo $results['course']['value']->getStatusCode();
    $json = json_decode((string)$results['course']['value']->getBody());
    $course_array = $json->data->courses;
    $course_rows = $json->data->rows_returned;

    $json = json_decode((string)$results['professor']['value']->getBody());
    $professor_array = $json->data->professors;
    $professor_rows = $json->data->rows_returned;








     headernav(); ?>

  <center><h1>SIGN UP</h1></center>

     <form action="newcoursethisyearasync.php" method="post">

       <div class='form-group'>
           <label>id_course</label>
           <select name='id_course'>
             <?php for ($x = 0; $x < $course_rows; $x++) { ?>
             <option value="<?php print_r($course_array[$x]->id); ?>">
               <?php print_r($course_array[$x]->name); ?>
             </option>
           <?php } ?>
           </select>
         </div>

             <div class='form-group'>
                 <label>id_responsible_prof</label>
                 <select name='id_responsible_prof'>
                   <?php for ($x = 0; $x < $professor_rows; $x++) { ?>
                   <option value="<?php print_r($professor_array[$x]->id); ?>">
                     <?php print_r($professor_array[$x]->fullname);  ?>
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
