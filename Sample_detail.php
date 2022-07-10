<?php
session_start();
include 'menu.php';
$db_hostname = 'localhost';
$db_database = 's2172876';
$db_username = 's2172876';
$db_password = '123456';
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
echo "<h3>Project:$row[0]</h3>";
echo "<h3>Detail annotation form:</h3>";
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
echo "<h3>This sample was used as part of the following experiment(s):</h3>";
// define query sentence
$compsel = "select * from DEGSummary where (Group1 LIKE '%".$_GET['id']."%' OR Group2 LIKE '%".$_GET['id']."%')";
// query database
$result = mysql_query($compsel);
$rows = mysql_num_rows($result);
$row = mysql_fetch_row($result);
for($j = 0 ; $j < $rows ; ++$j){
  echo "<a href='Exp_detail.php?id=$row[0]'>$row[0]</a>";
  echo "<br>";
}
?>
