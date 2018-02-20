<?php
  include 'header.html';
  echo "<div class='row'>&nbsp</div>";

  echo "<div class='row'>";
    echo "<div class='form-group'>";
      echo "<h2>Insert or Update Application Counts</h2>";
    echo "</div>";
  echo "</div>";

  echo "<div class='row'>";
    echo "<form action='AddUpdate.php'>";
      echo "<div class='form-check form-check-inline'>";
        echo "<label class='form-check-label' for='New'>";
          echo "<input class='form-check-input' id='New' name='formtype' type='radio' value='new' checked>New";
        echo "</label>";
      echo "</div>";
      echo "<div class='form-check form-check-inline'>";
        echo "<label class='form-check-label' for='Update'>";
          echo "<input class='form-check-input' id='Update' name='formtype' type='radio' value='update'>Update";
        echo "</label>";
      echo "</div>";


      echo "<div class='form-group'>";
        echo "<label class='control-label' for='date'>Date</label>";
        echo "<div class='input-group'>";
        echo "<div class='input-group-addon'>";
        echo "<i class='fa fa-calendar'>";
        echo "</i>";
        echo "</div>";
        echo "<input class='form-control' id='date' name='date' placeholder='yyyy-mm-dd' type='text'/>";
        echo "</div>";
      echo "</div>";

      echo "<div class='form-row'>";
        echo "<div class='form-group col-md-4'>";
          echo "<label class='' for='applyweb'>Applyweb Count</label>";
          echo "<input class='form-control' id='applyweb' type='number' name='applyweb' min='0'>";
        echo "</div>";
        echo "<div class='form-group col-md-4'>";
          echo "<label class='' for='coalition'>Coalition Count</label>";
          echo "<input class='form-control' id='coalition' type='number' name='coalition' min='0'>";
        echo "</div>";
      echo "</div>";

      echo "<button type='submit' class='btn btn-primary'>Submit</button>";

    echo "</form>";
  echo "</div>";
  include 'footer.html';
?>
