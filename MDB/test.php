<?php
session_start();
include 'menu.php';
echo<<<_HEAD1
<html>
<style>
table.dataTable thead .sorting:after,
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_asc_disabled:after,
table.dataTable thead .sorting_asc_disabled:before,
table.dataTable thead .sorting_desc:after,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_desc_disabled:after,
table.dataTable thead .sorting_desc_disabled:before {
  bottom: .5em;
}
</style>

<body>
_HEAD1;

$db_hostname = 'localhost';
$db_database = 's2172876';
$db_username = 's2172876';
$db_password = '123456';

// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
// throw error message
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$query = "select * from DEGSummary limit 100;";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
$smask = $_SESSION['supmask'];

if($rows == 0) {
    echo "No item found under this criteria";
}

// construct result table
echo "<table id='dtBasicExample' class='table table-striped table-bordered table-sm' cellspacing='0' width='100%'>";
echo "<thead>";
echo "<tr>";
echo "<th class='th-sm'>Experiment ID</th>";
echo "<th class='th-sm'>Project ID</th>";
echo "<th class='th-sm'>Conditions</th>";
echo "<th class='th-sm'>The number of DEGs</th>";
echo "<th class='th-sm'>The number of up-regulated genes</th>";
echo "<th class='th-sm'>The number of down-regulated genes</th>";
echo "<th class='th-sm'>The number of non DEGs</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
// for loop to present each row
for($j = 0 ; $j < $rows ; ++$j)
    {
        $row = mysql_fetch_row($result);
        echo "<tr>\n";
        echo "<td><a href='Exp_detail.php?id=$row[0]'>$row[0]</a></td>\n";
        echo "<td><a href='project_detail.php?id=$row[1]'>$row[1]</a></td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[5]</td>\n";
        echo "<td>$row[6]</td>\n";
        echo "</tr>\n";
    }
echo "</tboey>";
echo "<tfoot>";
echo "<tr>";
echo "<th>Experiment ID</th>";
echo "<th>Project ID</th>";
echo "<th>Conditions</th>";
echo "<th>The number of DEGs</th>";
echo "<th >The number of up-regulated genes</th>";
echo "<th>The number of down-regulated genes</th>";
echo "<th>The number of non DEGs</th>";
echo "</tr>";
echo "</tfoot>";
echo "</table>";

?>

<script type="text/javascript">
$(document).ready(function () {
  $('#dtBasicExample').DataTable();
  $('.dataTables_length').addClass('bs-select');
});
</script>