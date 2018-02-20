<?php
include 'header2.html';

//Functions
function round_to_3dp($number){
  return number_format((float)$number, 3, '.', '');
}

function format_number_int($number){
  return number_format((float)$number, 0, '.', ',');
}

/* Attempt MySQL server connection. */
$link = mysqli_connect("localhost","root","uai1111!","applications");
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

//VARIABLES
$currentMonth = date("m");
$currentYear = date("Y");

$ytd_applyweb=0;
$py_ytd_applyweb=0;
$ytd_coalition=0;
$py_ytd_coalition=0;
$mtd_applyweb=0;
$py_mtd_applyweb=0;
$mtd_coalition=0;
$py_mtd_coalition=0;

//MTD variables
$MTDapplyweb = 0;
$MTDPYapplyweb = 0;
$MTDcoalition = 0;
$MTDPYcoalition = 0;
$MTDawRatio = 0;
$MTDcoalRatio = 0;
$MTDgrand = 0;
$MTDPYgrand = 0;
$MTDgrandRatio = 0;

if(isset($_GET['month'])) $currentMonth=$_GET['month'];
if(isset($_GET['year'])) $currentYear=$_GET['year'];

// Make Current Month for Display
$currentMonthName = date("F",mktime(0, 0, 0, $currentMonth, 1, 2000));
$currentMonthNameShort = date("M",mktime(0, 0, 0, $currentMonth, 1, 2000));
//sprintf month to be 2 digits
$currentMonth = sprintf('%02d',$currentMonth);
//previous year variables
$previousYear = $currentYear - 1;
//previous Month
$previousMonth = 0;
if($currentMonth > 1){
  $previousMonth = sprintf('%02d',$currentMonth - 1);
} else {
  $previousMonth = 0;
}

  //get most recent day for the month
  $currentMonthLastDay = 1;
  $sql = "SELECT date FROM appCounts WHERE date LIKE '$currentYear-$currentMonth%' ORDER BY date DESC;";
  if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_array($result);
    $currentMonthLastDay = substr($row['date'], -2);
    //echo $currentMonthLastDay . "<br/>";
  } else{
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
  }

  //current year totals
  $sql = "SELECT SUM(applyweb) as sum_applyweb, SUM(coalition) as sum_coalition FROM appCounts WHERE date <= '$currentYear-$currentMonth-$currentMonthLastDay' AND date >= '$currentYear-01-01';";
  if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_array($result);
    $ytd_applyweb = $row['sum_applyweb'];
    $ytd_coalition = $row['sum_coalition'];
  } else{
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
  }

  //previous year totals
  $sql = "SELECT SUM(applyweb) as sum_applyweb, SUM(coalition) as sum_coalition  FROM appCounts WHERE date <= '$previousYear-$currentMonth-$currentMonthLastDay' AND date >= '$previousYear-01-01';";
  if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_array($result);
    $py_ytd_applyweb = $row['sum_applyweb'];
    $py_ytd_coalition = $row['sum_coalition'];
  } else{
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
  }

  //current month total
  $sql = "SELECT SUM(applyweb) as sum_applyweb, SUM(coalition) as sum_coalition  FROM appCounts WHERE date >= '$currentYear-$currentMonth-01' AND date <= '$currentYear-$currentMonth-$currentMonthLastDay';";
  if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_array($result);
    $mtd_applyweb = $row['sum_applyweb'];
    $mtd_coalition = $row['sum_coalition'];
  } else{
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
  }

  //previous month totals from last current date on file for the month
  $sql = "SELECT SUM(applyweb) as sum_applyweb, SUM(coalition) as sum_coalition  FROM appCounts WHERE date >= '$previousYear-$currentMonth-01' AND date <= '$previousYear-$currentMonth-$currentMonthLastDay';";
  if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_array($result);
    $py_mtd_applyweb = $row['sum_applyweb'];
    $py_mtd_coalition = $row['sum_coalition'];
  } else{
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
  }

  //----- Form for selecting dates ---------------
  echo "<br/>";
  echo "<form action='index.php'>";
    echo "<div class='form-row align-items-center'>";
      echo "<b>Select Month/Year</b>";
      echo "<div class=' col-auto'>";
        // echo "<label for='month'>Month</label>";
        echo "<select class='form-control' id='month' name='month'>";
          for ($i=1;$i<13;$i++){
              echo "<option value='";
              if ($i == $currentMonth){
                echo $i . "' selected='selected'>" . date("F", mktime(0,0,0,$i,1,$currentYear));
              } else {
                echo $i . "'>" . date("F", mktime(0,0,0,$i,1,$currentYear));
              }
                echo "</option>";
            }
        echo "<select/>";
      echo "</div>";
      echo "<div class=' col-auto'>";
        // echo "<label for='year'>Year</label>";
        echo "<input class='form-control' id='year' type='number' name='year' min='2015' max='2019' value='" . $currentYear . "'></div><div class='col'>";
        echo "<button type='submit' class='btn btn-primary'>Submit</button>";
      echo "</div>";
      echo "<div class=' col-auto'>";

      echo "</div>";
    echo "</div>";
  echo "</form>";
  //----- end form -----------------
  echo "<br>";

//START CHART

$sql = "SELECT A.date AS date1, B.date AS date2, A.day AS day, A.applyweb AS applyweb1, B.applyweb AS applyweb2, A.coalition AS coalition1, B.coalition AS coalition2 FROM appCounts A, appCounts B WHERE A.date LIKE '$currentYear-$currentMonth%' AND B.date LIKE '$previousYear-$currentMonth%' AND A.day = B.day ORDER BY A.date ASC;";

$col_aw = $col_coal = $col_py_aw = $col_py_coal = $col_date = [];

if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_array($result)){
        $col_aw[] = $row['applyweb1'];
        $col_py_aw[] = $row['applyweb2'];
        $col_coal[] = $row['coalition1'];
        $col_py_coal[] = $row['coalition2'];
        $col_date[] = $row['date1'];
      }
    }
}

echo "<div id='container' style='width:100%; height:400px;'></div>";

//START table

$sql = "SELECT A.date AS date1, B.date AS date2, A.applyweb AS applyweb1, B.applyweb AS applyweb2, A.coalition AS coalition1, B.coalition AS coalition2 FROM appCounts A, appCounts B WHERE A.date LIKE '$currentYear-$currentMonth%' AND B.date LIKE '$previousYear-$currentMonth%' AND A.day = B.day ORDER BY A.date DESC;";

if($result = mysqli_query($link, $sql)){
  $count = mysqli_num_rows($result).count();
    if(mysqli_num_rows($result) > 0){

        echo "<div class='row'><table class='table table-hover'>";
            echo "<thead class='thead-dark'>";
              echo "<tr>";
                echo "<th></th>";
                echo "<th colspan='5'>Applyweb</th>";
                echo "<th colspan='5'>Coalition</th>";
                echo "<th colspan='4'>Grand Totals</th>";
              echo "</tr>";
              echo "<tr>";
                echo "<th width='15%'>Date</th>";
                // applyweb columns
                echo "<th width=''>Apps</th>";
                echo "<th width=''>MTD</th>";
                echo "<th width=''>MTD Ratio</th>";
                echo "<th width=''>YTD</th>";
                echo "<th width=''>YTD Ratio</th>";
                // coalition columns
                echo "<th width=''>Apps</th>";
                echo "<th width=''>MTD</th>";
                echo "<th width=''>MTD Ratio</th>";
                echo "<th width=''>YTD</th>";
                echo "<th width=''>YTD Ratio</th>";
                // grand total columns
                echo "<th width=''>MTD </th>";
                echo "<th width=''>MTD Ratio</th>";
                echo "<th width=''>YTD</th>";
                echo "<th width=''>YTD Ratio</th>";
            echo "</tr></thead><tbody class=''>";

        //Starting table variables
        $count = 0;
        $aw_reduction = 0;
        $coal_reduction = 0;
        $py_aw_reduction = 0;
        $py_coal_reduction = 0;

        while($row = mysqli_fetch_array($result)){
          $count++;
          $aw = $row['applyweb1'];
          $pyaw = $row['applyweb2'];
          $coal = $row['coalition1'];
          $pycoal = $row['coalition2'];

          if($count == 1){
            //set mtd
            $MTDgrand = $mtd_applyweb + $mtd_coalition;
            $MTDPYgrand = $py_mtd_applyweb + $py_mtd_coalition;
            $MTDawRatio = $mtd_applyweb / $py_mtd_applyweb;
            $MTDcoalRatio = $mtd_coalition / $py_mtd_coalition;
            $MTDgrandRatio = $MTDgrand / $MTDPYgrand;
            //set ytd
            $YTDawRatio = ($ytd_applyweb) / ($py_ytd_applyweb);
            $YTDcoalRatio = ($ytd_coalition) / ($py_ytd_coalition);
            $ytd_all = $ytd_applyweb + $ytd_coalition;
            $py_ytd_all = $py_ytd_applyweb + $py_ytd_coalition;
            $YTDgrandRatio = ($ytd_all / $py_ytd_all);
            //set new reductions
            $aw_reduction = $aw;
            $coal_reduction = $coal;
            $py_aw_reduction = $pyaw;
            $py_coal_reduction = $pycoal;
          }
          else{
            //mtd reductions
            $mtd_applyweb -= $aw_reduction;
            $py_mtd_applyweb -= $py_aw_reduction;
            $mtd_coalition -= $coal_reduction;
            $py_mtd_coalition -= $py_coal_reduction;
            //ytd reductions
            $ytd_applyweb -= $aw_reduction;
            $py_ytd_applyweb -= $py_aw_reduction;
            $ytd_coalition -= $coal_reduction;
            $py_ytd_coalition -= $py_coal_reduction;
            //mtd calcs
            $MTDgrand = $mtd_applyweb + $mtd_coalition;
            $MTDPYgrand = $py_mtd_applyweb + $py_mtd_coalition;
            $MTDawRatio = $mtd_applyweb / $py_mtd_applyweb;
            $MTDcoalRatio = $mtd_coalition / $py_mtd_coalition;
            $MTDgrandRatio = $MTDgrand / $MTDPYgrand;
            //ytd calcs
            $YTDawRatio = ($ytd_applyweb) / ($py_ytd_applyweb);
            $YTDcoalRatio = ($ytd_coalition + $mtd_coalition) / ($py_ytd_coalition + $py_mtd_coalition);
            $ytd_all = $ytd_applyweb + $ytd_coalition - $aw_reduction - $coal_reduction;
            $py_ytd_all = $py_ytd_applyweb + $py_ytd_coalition - $py_aw_reduction - $py_coal_reduction;
            $YTDgrandRatio = ($ytd_all / $py_ytd_all);
            //set new reductions
            $aw_reduction = $aw;
            $coal_reduction = $coal;
            $py_aw_reduction = $pyaw;
            $py_coal_reduction = $pycoal;
          }
          //Build HTML Table Row
          echo "<tr>";
              echo "<td align='left'>" . date("D M j, Y",strtotime($row['date1'])) . "<br/><span class='previous'>" . date("D M j, Y",strtotime($row['date2'])) . "</span></td>";
              echo "<td align='right' class='typeBorder'>" . format_number_int($aw) . "<br/><span class='previous'>" . format_number_int($pyaw) . "</span></td>";
              echo "<td align='right' class='mtd'>" . format_number_int($mtd_applyweb) . "<br/><span class='previous'>" . format_number_int($py_mtd_applyweb) . "</span></td>";
              echo "<td align='right' class=''>" . round_to_3dp($MTDawRatio) . "</td>";
              echo "<td align='right' class='ytd'>" . format_number_int($ytd_applyweb) . "<br/><span class='previous'>" . format_number_int($py_ytd_applyweb) . "</span></td>";
              echo "<td align='right' class=''>" . round_to_3dp($YTDawRatio) . "</td>";
              echo "<td align='right' class='typeBorder'>" . format_number_int($coal) . "<br/><span class='previous'>" . format_number_int($pycoal) . "</span></td>";
              echo "<td align='right' class='mtd'>" . format_number_int($mtd_coalition) . "<br/><span class='previous'>" . format_number_int($py_mtd_coalition) . "</span></td>";
              echo "<td align='right' class=''>" . round_to_3dp($MTDcoalRatio) . "</td>";
              echo "<td align='right' class='ytd'>" . format_number_int($ytd_coalition) . "<br/><span class='previous'>" . format_number_int($py_ytd_coalition) . "</span></td>";
              echo "<td align='right' class=''>" . round_to_3dp($YTDcoalRatio) . "</td>";
              echo "<td align='right' class='mtd typeBorder'>" . format_number_int($MTDgrand) . "<br/><span class='previous'>" . format_number_int($MTDPYgrand) . "</span></td>";;
              echo "<td align='right' class=''>" . round_to_3dp($MTDgrandRatio) . "</td>";
              echo "<td align='right' class='ytd'>" . format_number_int($ytd_all) . "<br/><span class='previous'>" . format_number_int($py_ytd_all) . "</span></td>";
              echo "<td align='right' class=''>" . round_to_3dp($YTDgrandRatio) . "</td>";
          echo "</tr>";
        }
        echo "</tbody></table></div>";
        // Free result set
        mysqli_free_result($result);
    } else{
        echo "<div class='alert alert-warning' role='alert'>There was not enough data to generate the table.</div>";
    }
} else{
    echo "<div class='alert alert-danger' role='alert'>ERROR: Could not able to execute $sql. " . mysqli_error($link) . "</div>";
}
//END table

// Close connection
mysqli_close($link);

include 'footer.html';
?>
