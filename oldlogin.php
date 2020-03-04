<?php include "header.php"; ?>
<?php include "footer.php"; ?>
<?php   error_reporting(0);

session_start();
   session_unset();
  unset($_SESSION['id_acadsem']);
  unset($_SESSION['authtoken']);

?>
<!-- /navbar --><!DOCTYPE html>
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

<!-- content section will be here -->
<main role="main" class="container starter-template">

    <div class="row">
        <div class="col">

<h2>Login</h2>
   <form action="newscheduleraio_NO_ASYNC_v6.php" method="post">
            <div class='form-group'>
                <label for='username'>Username</label>
                <input type='username' class='form-control' id='username' name='username' placeholder='Enter username'>
            </div>

            <div class='form-group'>
                <label for='password'>Password</label>
                <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
            </div>

            <input type="submit" value="Submit">
        </form>

  <?php footernav(); ?>
