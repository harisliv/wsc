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

?>
<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<script src="https://kit.fontawesome.com/b86fda2231.js" crossorigin="anonymous"></script>

	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">

  <!-- Bootstrap 4 CSS and custom CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <style>
      body {
				font-family: 'Roboto Condensed', sans-serif;
			}


			.starter-template { padding: 1rem 1.5rem; }
      #logout{ display:none; }
      .now {color : red!important;}


      .flat-table {
		margin-bottom: 20px;
		border-collapse:collapse;
		font-family: 'Lato', Calibri, Arial, sans-serif;
		border: none;
                border-radius: 3px;
               -webkit-border-radius: 3px;
               -moz-border-radius: 3px;
	}
	.flat-table th, .flat-table td {
		box-shadow: inset 0 -1px rgba(0,0,0,0.25),
			inset 0 1px rgba(0,0,0,0.25);
	}
	.flat-table th {
		font-weight: normal;
		-webkit-font-smoothing: antialiased;
		padding: 1em;
		color: rgba(0,0,0,0.45);
		text-shadow: 0 0 1px rgba(0,0,0,0.1);
		font-size: 1.5em;
	}
	.flat-table td {
		color: #f7f7f7;
		padding: 0.7em 1em 0.7em 1.15em;
		text-shadow: 0 0 1px rgba(255,255,255,0.1);
		font-size: 1.4em;
	}
	.flat-table tr {
		-webkit-transition: background 0.3s, box-shadow 0.3s;
		-moz-transition: background 0.3s, box-shadow 0.3s;
		transition: background 0.3s, box-shadow 0.3s;
	}
	.flat-table-1 {
		background: #336ca6;
	}


	.flat-table-2 {
		background: #f06060;
	}
	.flat-table-3 {
		background: #52be7f;
	}


	.sidenav {
    width: 30%;
    position: fixed;
    z-index: 1;
    top: 64px;
    left: 0px;
    background: #212529;
    overflow-y: hidden;
    padding: 0px 23px;
    height: 97vh;
}

input#submit {
    margin-top: 8px;
}

.main {
	width: 100%;
}

	.sidenav a {
	  padding: 6px 8px 6px 16px;
	  text-decoration: none;
	  font-size: 25px;
	  color: #2196F3;
	  display: block;
	}

	#exampleFormControlSelect2 {
	    overflow: auto;
			height: 76vh;
	    position: relative;
			}

	#exampleFormControlSelect2::-webkit-scrollbar {
  width: 10px;
}

#exampleFormControlSelect2::-webkit-scrollbar-thumb {
  background: #666;
  border-radius: 20px;
}

#exampleFormControlSelect2::-webkit-scrollbar-track {
  background: #ddd;
  border-radius: 20px;
}

	@media only screen and (max-height: 665px) {
		#exampleFormControlSelect2 {
		    height: 65vh;
		}

		.main {
			width: 74%;
		}
}

	.form-check.harisformdelete {
		padding-left: 0;
		margin: 5px;
}

.labelformcheck2{
	padding: 5px;

}

.harisformcheck label {
    margin-bottom: 0;
}

	.form-check.harisformcheck {
	    padding-left: 0;
	    border-radius: 25px;
	    margin: 5px;
	}

	.alert.alert-danger {
	    margin-left: 25%;
	}



	.labelformcheck input[type=checkbox], .labelformcheck2 input[type=checkbox] {
		display: none;
	}

	.red{background-color:yellow;}
	.green{
		border: 2px solid;
		    padding: 3px;
				background-color: #dc3a4b!important;
		}

		.bg-dark {
    background-color: #212529!important;
	}

	.navbar{
		padding: 0.75rem 1rem!important;
	}

	select#exampleFormControlSelect1 {
    margin-right: 25px;
}

.logoharis{
		font-size: 13px;
    color: white;
    margin-right: 15px;
    margin-top: 1px;
    border: 2px solid;
    border-radius: 50%;
    padding: 8px;
}

 .whitelabel {
	 color:white;
	 font-size: 0.85rem;
 }

 label.labelformcheck2.LAB {
    background-color: dodgerblue;
	}

	label.labelformcheck2.THEORY {
		 background-color: aquamarine;
	 }

	 label.labelformcheck2.PRACTICE {
			background-color: #d578ec;
	 }

	 option.practice {
	     color: #ff00bc;
	 }

	 option.theory {
	     color: green;
	 }

	 option.lab {
	     color: blue;
	 }




.alert.alert-danger {
    text-align: center;
		width:60vw;
}

.btn-primary:disabled {
    background-color: #5383b7;
    border-color: #164373;
}



  </style>

</head>

<body>
  <main role="main" class="container starter-template">

      <div class="row">
          <div class="col">
  <?php
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
                  <div class="form-check harisformdelete"><label class="labelformcheck2 <?php echo $scheduled[0]->type_division ?>" >

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

                }

              }

              ?>

            </td>
            <?php } ?>
          </tr>
        <?php } ?>

        </tbody>
      </table>




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
