
<?php
include 'header.html';
/* Attempt MySQL server connection. */
$link = mysqli_connect("localhost","root","uai1111!","applications");

//default values to 0 for counts
$applyweb = 0;
$coalition = 0;


// set variables
if(isset($_GET['formtype'])) $formType=$_GET['formtype'];
if(isset($_GET['date'])) $formDate=$_GET['date'];
if(isset($_GET['applyweb'])) $applyweb=$_GET['applyweb'];
if(isset($_GET['coalition'])) $coalition=$_GET['coalition'];
//splice day off of date
if($formDate){
  $day = substr($formDate, -2);
}else{
  $day = 0;
}

//Date Format Checker
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

if (validateDate($formDate)){
  //Change date to string
  //$formDate = (string)$formDate;

  if ($formType == "new"){
    // Attempt insert query execution
    $sql = "INSERT INTO appCounts (date, applyweb, coalition, day) VALUES (CAST('". $formDate ."' AS DATE), $applyweb, $coalition, $day)";
    if(mysqli_query($link, $sql)){

        echo "<div class='alert alert-success' role='alert'>Record inserted successfully.<br/>";
          echo "<b>Type:</b> " . $formType . " <b>Date:</b> " . $formDate . " <b>Applyweb Count:</b> " . $applyweb . " <b>Coalition Count:</b> " . $coalition;
        echo "</div>";
    } else{
        echo "<div class='alert alert-danger' role='alert'>ERROR: Could not able to execute<br/> $sql <br/>. " . mysqli_error($link) . "</div>";
    }
// Close connection
mysqli_close($link);
  } else if ($formType == "update"){
    // Attempt insert query execution
    $sql = "UPDATE appCounts SET  applyweb = $applyweb, coalition = $coalition, day = $day WHERE date = CAST('". $formDate ."' AS DATE)";
    //(date, applyweb, coalition) VALUES (CAST('". $formDate ."' AS DATE), $applyweb, $coalition)";
    if(mysqli_query($link, $sql)){
      echo "<div class='alert alert-success' role='alert'>Record inserted successfully.<br/>";
        echo "<b>Type:</b> " . $formType . " <b>Date:</b> " . $formDate . " <b>Applyweb Count:</b> " . $applyweb . " <b>Coalition Count:</b> " . $coalition;
      echo "</div>";
    } else{
        echo "<div class='alert alert-danger' role='alert'>ERROR: Could not able to execute<br/> $sql <br/>. " . mysqli_error($link) . "</div>";
    }
  }
} else{
  echo "<div class='alert alert-warning' role='alert'><h3>You have not provided a correct date format. Please fix.</h3></div>";
}

echo "<a class='btn btn-primary' href='form.php' role='button'>Reload Form</a>";
include 'footer.html';
?>
