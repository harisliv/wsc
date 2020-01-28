<?php

function footernav()
{
  ?>
</div>
</div>

</main>
</body>

  <!-- jQuery & Bootstrap 4 JavaScript libraries -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <!-- jquery scripts will be here -->
<script>

$(':input[type="checkbox"][class="form-check-input inputjsred"]').change(function () {

    if ($(this).is(":checked")) {
        $(this).closest(".labelformcheck").addClass('red');
    }
    else {
        $(this).closest(".labelformcheck").removeClass('red');
    };

});

$(':input[type="checkbox"][class="form-check-input inputjsgreen"]').change(function () {

    if ($(this).is(":checked")) {
        $(this).closest(".labelformcheck2").addClass('green');
    }
    else {
        $(this).closest(".labelformcheck2").removeClass('green');
    };

});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
      animation: true,
      delay: {show: 100, hide: 100}
    });
    $('button').on('mousedown', function(){
      $('[data-toggle="tooltip"]').tooltip('hide');
    });
    $('[data-toggle="tooltip"]').on('mouseleave', function(){
      $('[data-toggle="tooltip"]').tooltip('hide');
    });
});

  function myFunctionDelete(){
            document.getElementById("submit").value= "ΔΙΑΓΡΑΦΗ";
            }

  function myFunctionSubmit(){
            document.getElementById("submit").value= "ΥΠΟΒΟΛΗ";
            }

            /*
            function isChecked(checkbox, submit) {
              document.getElementById(submit).disabled = !checkbox.checked;
            }
            */



function postConfirm() {
  if ($('.inputjsgreen').is(':checked')) {
    if (confirm('Έχετε επιλέξει τμήμα για διαγραφή')) {
        yourformelement.submit();
    } else {
        return false;
    }
  }
}



</script>
    <?php
    }
?>
