<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      headernav();
      ?>

<form action="showsinglecourse.php" method="post">
  <div class='form-group'>
    <center><h1>SEARCH COURSE BY ID</h1></center>
      <input type='id' class='form-control' name='id' placeholder='Enter Course ID'>
  </div>
  <input type="submit" value="Submit">

  <?php footernav(); ?>
