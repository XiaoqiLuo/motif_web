<?php
session_start();
include 'menu.php';
require_once 'page.php';
$db_hostname = 'localhost';
$db_database = 'ceibio_wp775';
$db_username = 'ceibio_wp775';
$db_password = 'RNAlab2022';
// pre define $cid
$cid = 1;
// if get id, then assign id to $cid
if(isset($_GET['id']) & isset($_GET['type']) & isset($_GET['orgn'])) {
  $cid = $_GET['id'];
  $utr_type = $_GET['type'];
  $orgn = $_GET['orgn'];
}

// echo $_GET['orgn'];
echo<<<_HEAD1

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">
 
<style type="text/css">
/* Style the buttons that are used to open and close the accordion panel */
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
.active, .accordion:hover {
  background-color: #ccc;
}

/* Style the accordion panel. Note: hidden by default */
.panel {
  padding: 0 18px;
  background-color: white;
  display: none;
  overflow: hidden;
}
</style>
<body>
_HEAD1;

// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$setpar = isset($_GET['id']); 


echo <<<_MAIN1
_MAIN1;
echo "<h2>Motif detail inforamtion </h2>";
echo "Motif:".$_GET['id']."<br>";

$compsel = "select * from mirna where motif='".$_GET['id']."';";
// query database
$result = mysql_query($compsel);
if(!result) die("unable to process query: " . mysql_error());
$row = mysql_fetch_row($result);



echo <<<_TAIL1
<button class="accordion">Micro-RNA associated motif</button>
<div class="panel">
<!--Table header-->
    <table data-toggle="table" data-pagination="true" data-toolbar=".toolbar" id='deg' >
    <thead>
    <tr>
    <th data-sortable="true">Micro-RNA</th> 
    <th data-sortable="true">ID</th> 
    <th data-sortable="true">Organism</th> 
    <th data-sortable="true">Sequence</th> 
    <th data-sortable="true">Seed</th> 
    <th data-sortable="true">Motif</th> 
    </tr>
    </thead>

_TAIL1;

if($setpar) {
  echo $_GET['orgn'];
  if($_GET['orgn']=='mouse'){
  // define query sentence
  $compsel = "select * from mirna where motif='".$_GET['id']."' AND organism = 'Mus musculus';";
  }else{
  $compsel = "select * from mirna where motif='".$_GET['id']."' AND organism = 'Homo sapiens';";

  }
  
  // query database
  $result = mysql_query($compsel);
  if(!result) die("unable to process query: " . mysql_error());
  $rows = mysql_num_rows($result);
  for($j = 0 ; $j < $rows ; ++$j){
    $row = mysql_fetch_row($result);
    echo "<tr>\n";
    echo "<td>$row[0]</td>\n";
    echo "<td>$row[1]</td>\n";
    echo "<td>$row[2]</td>\n";
    echo "<td>$row[3]</td>\n";
    echo "<td>$row[4]</td>\n";
    echo "<td>$row[5]</td>\n";
    echo "</tr>\n";
  }
  echo "</tbody>";
  echo "</table>";
}
// echo $compsel;
echo "</table>";
echo "<br>";
echo "<br>";
echo "</div>";

echo <<<_TAIL2

<button class="accordion">Contrast</button>
<div class="panel">
<!--Table header-->
    <table data-toggle="table" data-pagination="true" data-toolbar=".toolbar" id='deg' >
    <thead>
    <tr>
    <th data-sortable="true">Contrast</th> 
    </tr>
    </thead>


_TAIL2;


  // define query sentence
  $compsel1 = "select * from motif where motif='".$_GET['id']."' AND type= '".$_GET['type']."'";

  // query database
  $result1 = mysql_query($compsel1);
  if(!result1) die("unable to process query: " . mysql_error());
  $row1 = mysql_fetch_row($result1);
  $str_arr = explode (",", $row1[3]);
  for($k = 0 ; $k < count($str_arr); ++$k){
        echo "<tr>\n";
        echo "<td><a href='Exp_detail.php?id=$str_arr[$k]'>$str_arr[$k]\n";
        echo "<tr>\n";
    }

echo "</tbody>";
?>
<!-- accordion -->
<script type="text/javascript">
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
<!-- sort atble -->
<script>
  var $table = $('#deg')
  $(function() {
    $table.bootstrapTable({
      sortReset: true
    })

    $('#sortReset').change(function () {
      $table.bootstrapTable('refreshOptions', {
        sortReset: $('#sortReset').prop('checked')
      })
    })
  })

</script>