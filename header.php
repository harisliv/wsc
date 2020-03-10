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

	<?php
	if(!isset($_SESSION["id_acadsem"]) && !isset($_SESSION["learn_sem"])) {

				$_SESSION["id_acadsem"] = 1;
				$_SESSION["learn_sem"] = "B";
				$_SESSION["lektiko_acadsem"] = "ΧΕΙΜΕΡΙΝΟ ΕΞΑΜΗΝΟ 2017";
	}
					//echo "sess acad sem" . $_SESSION["id_acadsem"];
					//echo "sess learn sem" . $_SESSION["learn_sem"];
	if(isset($_POST["form2"])){
		$_SESSION['refresh_post'] = '';
		$_SESSION['refresh_delete'] = '';
		//unset($_SESSION['refresh_post']);
		//unset($_SESSION['refresh_delete']);
		//echo "<br> post acad sem " . $_POST["id_acadsem"];
		//echo "<br> post learn sem " . $_POST["learn_sem"];
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

<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<script src="https://kit.fontawesome.com/b86fda2231.js" crossorigin="anonymous"></script>

	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">

  <!-- Bootstrap 4 CSS and custom CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <style>
      body { padding-top: 5rem;
				font-family: 'Roboto Condensed', sans-serif;
			}


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
	margin-left: 31%;
	width: 82%;
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
	cursor: pointer;

}

.harisformcheck label {
    margin-bottom: 0;
		cursor: pointer;
}

	.form-check.harisformcheck {
	    padding-left: 0;
	    border-radius: 25px;
	    margin: 5px;
	}

	.alert.alert-danger {
	    margin-left: 25%;
	}

	 .labelformcheck:hover
	 {
		border-bottom: 2px solid;
		margin-bottom: -2px;
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



label.labelformcheck2:hover {
	 background-color: #dc3a4b;
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

<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<span class="logoharis">
				<i class="fas fa-code"></i>
			</span>
			<div class="navbar-nav mr-auto navbarharis">
				          <?php  if(isset($_SESSION["authtoken"])){ ?>
            <a class="nav-item nav-link" href="http://localhost/wsc/simple_table.php" target="_blank">Εκτύπωση</a>
					<?php  }?>
				</div>
        <div class="navbar-nav ml-auto">
          <?php  if(isset($_SESSION["authtoken"])){ ?>
					<?php  }?>

							<form action="#" method="post" class='form-inline'>
								<div class="form-group">
								<select class="form-control" id="exampleFormControlSelect1" name='id_acadsem'>
									<?php
									if(isset($_SESSION["id_acadsem"])) {?>
										<option value="<?php echo $_SESSION["id_acadsem"]?>"><b><?php echo $_SESSION["lektiko_acadsem"]?></b></option>
									<?php } ?>
				          <?php for ($x = 0; $x < $json3->data->rows_returned; $x++) {
										if($json3->data->acadsems[$x]->id !== $_SESSION["id_acadsem"]){?>

										<option value="<?php echo $json3->data->acadsems[$x]->id; ?>">
					            <?php echo $json3->data->acadsems[$x]->lektiko_acadsem; ?>
					          </option>
				        <?php } }?>
				        </select>
								</div>
								<div class="form-group">
								<select class="form-control" id="exampleFormControlSelect1" name="learn_sem">
									<?php
									$learnsem_eng = array("A", "B", "C", "D", "E", "F", "G");
									$learnsem_gr = array("Α", "Β", "Γ", "Δ", "Ε", "Ζ", "Η");
									if(isset($_SESSION["learn_sem"])) {
										for($i = 0; $i < 7; $i++)
										{
											if($learnsem_eng[$i] == $_SESSION["learn_sem"]){?>
										<option value="<?php echo $_SESSION["learn_sem"]?>"><b><?php echo $learnsem_gr[$i]?></b></option>
									<?php }
								}
							}
							for($i = 0; $i < 7; $i++)
									{
										if($learnsem_eng[$i] !== $_SESSION["learn_sem"]){?>

									<option value="<?php echo $learnsem_eng[$i] ?>"><?php echo $learnsem_gr[$i] ?></option>
								<?php }} ?>

								</select>
							</div>

							<input class = "btn btn-primary" type="submit" value="ΥΠΟΒΟΛΗ" name="form2">
							</form>

        </div>
    </div>
</nav>



<main role="main" class="container starter-template">

    <div class="row">
        <div class="col">
<?php
}
?>
