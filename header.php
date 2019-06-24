<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(0);


function headernav()
{

  ?>
<!DOCTYPE html>
<head>

  <!-- Bootstrap 4 CSS and custom CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="custom.css" />
  <style>
      body { padding-top: 5rem; }
      .starter-template { padding: 3rem 1.5rem; }
      #logout{ display:none; }

  </style>

</head>

<body>

<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <?php  if(isset($_SESSION["authtoken"]) && isset($_SESSION["id_acadsem"])){ ?>
            <a class="nav-item nav-link" href="http://localhost/wsc/searchcoursebyid.php">Search Course by ID</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/showallcourses.php">Show All Courses</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/newroomavailable.php">New Room availability</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/newcoursethisyear.php">New course this year</a>
            <a class="nav-item nav-link" href="http://localhost/wsc/newscheduler.php">Scheduler</a>

            <?php  }?>
        </div>
    </div>
</nav>

<main role="main" class="container starter-template">

    <div class="row">
        <div class="col">
<?php
}
?>
