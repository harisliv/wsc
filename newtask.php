<?php
session_start();
echo $_SESSION["authtoken"]; 

?>

<h2>Login</h2>
   <form action="newtaskform.php" method="post">

           <div class='form-group'>
               <label for='title'>fullname</label>
               <input type='title' class='form-control' id='title' name='title' placeholder='Enter title'>
           </div>

           <div class='form-group'>
               <label for='description'>description</label>
               <input type='description' class='form-control' id='description' name='description' placeholder='Enter description'>
           </div>

           <div class='form-group'>
               <label for='deadline'>deadline</label>
               <input type='deadline' class='form-control' id='deadline' name='deadline' placeholder='deadline'>
           </div>

           <div class='form-group'>
               <label for='completed'>completed</label>
               <input type='completed' class='form-control' id='completed' name='completed' placeholder='completed'>
           </div>

            <input type="submit" value="Submit">
        </form>
