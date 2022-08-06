<?php
session_start();
include 'menu.php';
$db_hostname = 'localhost';
$db_database = 'ceibio_wp775';
$db_username = 'ceibio_wp775';
$db_password = 'RNAlab2022';
// pre define $cid
$cid = 1;
// if get id, then assign id to $cid
if(isset($_GET['id'])) {
  $cid = $_GET['id'];
}
echo<<<_HEAD1
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">

<style type="text/css">
/* Style the buttons that are used to open and close the accordion panel */
/* https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_accordion */
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
include 'menuf.php';
// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$setpar = isset($_GET['id']); 

$compsel = "select * from project_subset where projectID='".$_GET['id']."';";

// query database
$result = mysql_query($compsel);
if(!result) die("unable to process query: " . mysql_error());

$row = mysql_fetch_row($result);

echo <<<_MAIN1
_MAIN1;
echo "<h6>Project detail </h6>";
echo "<h3><small>$row[4]</small></h3>";
echo "<p>ID:$row[0]</p>";
echo "<p>Organism:$row[1]</p>";
echo "<p>Abstract:$row[5]</p>";
echo "<button class=\"accordion\">Sample annotation</button>
  <div class=\"panel\">";
// define query sentence
$compsel = "select * from sample where projectID='".$_GET['id']."';";
// query database
$result = mysql_query($compsel);
if(!result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
if($rows>0){
echo "
    <!--Table header-->
  <table id=\"table\" data-toggle=\"table\" data-pagination=\"true\">
  <thead>
    <tr>
      <th>Sample ID</th> 
      <th>ProjectID</th> 
      <th>Tissue</th> 
      <th>Cell line</th> 
      <th>Cell type</th> 
      <th>Condition</th> 
      <th>Treatment</th> 
      <th>Treatment duration</th>
      <th>Genotype</th>
      <th>Subtype</th>
      </tr>
      </thead>
  ";
  // // define query sentence
  // $compsel = "select * from sample where projectID='".$_GET['id']."';";
  // // query database
  // $result = mysql_query($compsel);
  // if(!result) die("unable to process query: " . mysql_error());
  // $rows = mysql_num_rows($result);
  for($j = 0 ; $j < $rows ; ++$j){
    $row = mysql_fetch_row($result);
    echo "<tr>\n";
    echo "<td><a href='Sample_detail.php?id=$row[0]'>$row[0]</a></td>\n";
    echo "<td>$row[1]</td>\n";
    echo "<td>$row[2]</td>\n";
    echo "<td>$row[3]</td>\n";
    echo "<td>$row[4]</td>\n";
    echo "<td>$row[5]</td>\n";
    echo "<td>$row[6]</td>\n";
    echo "<td>$row[7]</td>\n";
    echo "<td>$row[8]</td>\n";
    echo "<td>$row[9]</td>\n";
    echo "</tr>\n";
  }
  // end of table
  echo "</tbody>";
  echo "</table>";
  echo "<br>";
  echo "<br>";
}
if($rows==0){
  $compsel = "select * from GTEx where study='".$_GET['id']."';";
  // query database
  $result = mysql_query($compsel);
  if(!result) die("unable to process query: " . mysql_error());
  $rows = mysql_num_rows($result);

  if($rows!=0){
    echo " <table id=\"table\" data-toggle=\"table\" data-pagination=\"true\">
    <thead id=\"head-table\">
      <tr>
      <th>Sample ID</th>
      <th>Accession</th>
      <th>Study</th>
      <th>Sex</th>
      <th>AGE</th>
      <th>SUBJID</th>
      </tr>
    </thead>
    <tbody>";
    
    $compsel = "select * from GTEx where study='".$_GET['id']."';";
    $result = mysql_query($compsel);
    $rows = mysql_num_rows($result);
    for($j = 0 ; $j < $rows ; ++$j)
    {
        $row = mysql_fetch_row($result);
        // echo $row;
        echo "<tr>\n";
        
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[5]</td>\n";
       
        echo "<tr>\n";
    }
    echo "</tbody>";
    echo "</table>";
    echo "<br>";
    echo "<br>";
  }
  if($rows==0){
    echo " <table id=\"table\" data-toggle=\"table\" data-pagination=\"true\">
    <thead id=\"head-table\">
      <tr>
        <th>Project ID</th>
        <th>Sample ID</th>
        <th>Project name</th>
        <th>Tissue</th>
        <th>Tumor stage</th>
        <th>Vital status</th>
        <th>Sample status</th>
        <th>Tumor status</th>
        <th>Pathological staging (N)</th>
        <th>Clinical staging (M)</th>
        <th>Clinical stage</th>
        <th>Pathological stage </th>
        <th>Pathological staging (T)</th>
        <th>Histological type</th>
        <th>Submitter ID</th>
      </tr>
    </thead>
    <tbody>";
    
    $compsel = "select * from TCGAannotation where study='".$_GET['id']."';";
    $result = mysql_query($compsel);
    $rows = mysql_num_rows($result);
    for($j = 0 ; $j < $rows ; ++$j)
    {
        $row = mysql_fetch_row($result);
        // echo $row;
        echo "<tr>\n";
        
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[5]</td>\n";
        echo "<td>$row[6]</td>\n";
        echo "<td>$row[7]</td>\n";
        echo "<td>$row[8]</td>\n";
        echo "<td>$row[9]</td>\n";
        echo "<td>$row[10]</td>\n";
        echo "<td>$row[11]</td>\n";
        echo "<td>$row[12]</td>\n";
        echo "<td>$row[13]</td>\n";
        echo "<td>$row[14]</td>\n";
        echo "<td>$row[15]</td>\n";
        echo "<tr>\n";
    }
    echo "</tbody>";
    echo "</table>";
    echo "<br>";
    echo "<br>";
  }
}

echo "</div>";
echo <<<_TAIL2

<button class="accordion">Comparison</button>
<div class="panel">
<!--Table header-->
<table id="table" data-toggle="table" data-pagination="true" class="text-break">
<thead>
  <tr>
    <th>ID</th> 
    <th>Comparison</th> 
    <th>Differential expressed genes</th> 
    <th>Up regulated</th> 
    <th>Down regulated</th> 
    <th>Non Differential expressed genes</th> 
    </tr>
    </thead>

_TAIL2;

if($setpar) {
    // define query sentence
    $compsel1 = "select * from DEGSummary where projectID='".$_GET['id']."';";
    // query database
    $result1 = mysql_query($compsel1);
    if(!result1) die("unable to process query: " . mysql_error());
    $rows1 = mysql_num_rows($result1);
    for($j = 0 ; $j < $rows1 ; ++$j){
      $row1 = mysql_fetch_row($result1);
      echo "<tr>\n";
      echo "<td><a href='Exp_detail.php?id=$row1[0]'>$row1[0]</a></td>\n";
      echo "<td class='text-break'>$row1[2]</td>\n";
      echo "<td>$row1[3]</td>\n";
      echo "<td>$row1[4]</td>\n";
      echo "<td>$row1[5]</td>\n";
      echo "<td>$row1[6]</td>\n";
      echo "</tr>\n";
    }
  }
  // end of table
  echo "</tbody>";
  echo "</table>";
  echo "<br>";
  echo "<br>";
  echo "</div>";
  // echo $compsel1;
?>
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
