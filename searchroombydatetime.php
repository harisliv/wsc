<?php include "header.php"; ?>
<?php include "footer.php"; ?>
      <?php
      session_start();
      headernav();
      ?>

<form action="searchroombydatetimeform.php" method="post">
  <div class='form-group'>
    <center><h1>SEARCH room BY date time</h1></center>
    <input type='day' class='form-control' name='day' placeholder='Enter day'>
    <input type='start_time' class='form-control' name='start_time' placeholder='Enter start_time'>
    <input type='room_code' class='form-control' name='room_code' placeholder='Enter room_code'>
  </div>
  <input type="submit" value="Submit">
</form>

  <?php footernav(); ?>
