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
// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$setpar = isset($_GET['id']); 


echo <<<_MAIN1
_MAIN1;
echo "<h2>Sample annotation information</h2>";

echo <<<_TAIL1

_TAIL1;

// define query sentence
$compsel = "select * from sample where sampleID='".$_GET['id']."';";
// query database
$result = mysql_query($compsel);
if(!result) die("unable to process query: " . mysql_error());
$row = mysql_fetch_row($result);
echo "<p>Project:$row[0]</p>";
echo "<p>Detail annotation form:</p>";
echo "<table class='table table-bordered'>";
echo "<tr>";
echo "<th>Tissue</th>"; 
echo "<th>Cell line</th>"; 
echo "<th>Cell type</th>"; 
echo "<th>Condition</th>";
echo "<th>Treatment</th>";
echo "<th>Treatment Duration</th>";
echo "<th>Genotype</th>"; 
echo "<th>Subtype</th>";
echo "</tr>";
for($j = 2 ; $j < 10 ; ++$j){
  echo "<td>$row[$j]</td>\n";
}
// end of table
echo "</table>";
echo "<br>";
echo "<p>This sample was used as part of the following experiment(s):</p>";
// define query sentence
$compsel = "select * from DEGSummary where (Group1 LIKE '%".$_GET['id']."%' OR Group2 LIKE '%".$_GET['id']."%')";

// query database
$result = mysql_query($compsel);
$rows = mysql_num_rows($result);
$row = mysql_fetch_row($result);
for($j = 0 ; $j < $rows ; ++$j){
  $row = mysql_fetch_row($result);
  echo "<a href='Exp_detail.php?id=$row[0]'>$row[0]</a>";
  echo "<br>";
}
?>
