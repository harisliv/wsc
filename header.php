<?php
session_start();
require "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(0);





function headernav()
{
	//if(isset($_SESSION["authtoken"])) {

	$client3 = new GuzzleHttp\Client();
	$res3 = $client3->request('GET', 'http://localhost/shedulerapi/controller/acad_sem.php',
	[
	'headers' => ['Authorization' => $_SESSION["authtoken"]]
	]
	);

	$body3 = $res3->getBody();
	$string3 = $body3->getContents();
	$json3 = json_decode($string3);
//}
  ?>
<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">

  <!-- Bootstrap 4 CSS and custom CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <style>
      body { padding-top: 5rem; }
      .starter-template { padding: 3rem 1.5rem; }
      #logout{ display:none; }
      .now {color : red!important;}
      .now:hover {color:cyan!important;}


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
	.flat-table-1 tr:hover {
		background: rgba(0,0,0,0.19);
	}
	.flat-table-2 tr:hover {
		background: rgba(0,0,0,0.1);
	}
	.flat-table-2 {
		background: #f06060;
	}
	.flat-table-3 {
		background: #52be7f;
	}
	.flat-table-3 tr:hover {
		background: rgba(0,0,0,0.1);
	}

  </style>

</head>

<body>

<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="http://localhost/wsc">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <?php  if(isset($_SESSION["authtoken"])){ ?>
            <a class="nav-item nav-link" href="http://localhost/wsc/searchcoursebyid.php">Search Course by ID</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/showallcourses.php">Show All Courses</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/newroomavailable.php">New Room availability</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/newcoursethisyear.php">New course this year</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/newscheduleraio.php">Scheduler</a>
					<?php  }?>

							<form action="#" method="post">
								<select name='id_acadsem'>
				          <?php for ($x = 0; $x < $json3->data->rows_returned; $x++) { ?>
										<option value="<?php print_r($json3->data->acadsems[$x]->id); ?>">
					            <?php print_r($json3->data->acadsems[$x]->lektiko_acadsem); ?>
					          </option>
				        <?php } ?>
				        </select>
								<select name="learn_sem">
								  <option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
									<option value="E">E</option>
									<option value="F">F</option>
									<option value="G">G</option>
									<option value="H">H</option>
								</select>
							<input type="submit" name="form2">
							</form>

        </div>
    </div>
</nav>

<?php $_SESSION["id_acadsem"] = 1;
			$_SESSION["learn_sem"] = "A";

				echo "sess elemnts" . $_SESSION["id_acadsem"];
				echo "sess elemnts" . $_SESSION["learn_sem"];
if(isset($_POST["form2"])){
	echo "<br> post elements " . $_POST["id_acadsem"];
	echo "<br> post elements " . $_POST["learn_sem"];
	//if(isset($_POST["id_acadsem"])) {
		$client2 = new GuzzleHttp\Client();
		$res2 = $client2->request('GET', 'http://localhost/shedulerapi/controller/acad_sem.php',
		[
		'headers' => ['Authorization' => $_SESSION["authtoken"]],
		'query' => ['id' => $_POST["id_acadsem"]]
		]
		);

		$body2 = $res2->getBody();
		$string2 = $body2->getContents();
		$json2 = json_decode($string2);
		$_SESSION["id_acadsem"] = $json2->data->acadsems[0]->id;
		$_SESSION["lektiko_acadsem"] = $json2->data->acadsems[0]->lektiko_acadsem;
		$_SESSION["learn_sem"] = $_POST["learn_sem"];
//}
} ?>

<main role="main" class="container starter-template">

    <div class="row">
        <div class="col">
<?php
}
?>
