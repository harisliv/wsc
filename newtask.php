<?php
session_start();
echo $_SESSION["authtoken"];

?>

<h2>Login</h2>
   <form action="newtaskform.php" method="post">

           <div class='form-group'>
               <label for='id'>Course ID</label>
               <input type='id' class='form-control' id='courseid' name='id' placeholder='Enter Course ID'>
           </div>

           <div class='form-group'>
               <label for='name'>name</label>
               <input type='name' class='form-control' id='name' name='name' placeholder='Enter name'>
           </div>

           <div class='form-group'>
               <label for='curr'>programma spoudwn</label>
               <input type='curr' class='form-control' id='curr' name='curr' placeholder='curr'>
           </div>

           <div class='form-group'>
               <label for='period'>period</label>
               <input type='period' class='form-control' id='period' name='period' placeholder='period'>
           </div>

           <div class='form-group'>
               <label for='active'>active</label>
               <input type='active' class='form-control' id='active' name='active' placeholder='active'>
           </div>

           <div class='form-group'>
               <label for='hours_theory'>hours_theory</label>
               <input type='hours_theory' class='form-control' id='hours_theory' name='hours_theory' placeholder='hours_theory'>
           </div>

           <div class='form-group'>
               <label for='hours_lab'>hours_lab</label>
               <input type='hours_lab' class='form-control' id='hours_lab' name='hours_lab' placeholder='hours_lab'>
           </div>

           <div class='form-group'>
               <label for='hours_practice'>period</label>
               <input type='hours_practice' class='form-control' id='hours_practice' name='hours_practice' placeholder='hours_practice'>
           </div>

            <input type="submit" value="Submit">
        </form>
