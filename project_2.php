<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>

<?php
session_start();
include 'menu.php';
$db_hostname = 'localhost';
$db_database = 's2172876';
$db_username = 's2172876';
$db_password = '123456';
echo<<<_HEAD1
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Project information</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">
  </head>
_HEAD1;
// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
// throw error message
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$query = "select * from project limit 3000;";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
$smask = $_SESSION['supmask'];

if($rows == 0) {
    echo "No item found under this criteria";
}

// construct project table
echo "<table style='width: 80%' data-pagination-v-align='top' id='table'
 data-toggle='table' data-pagination='true' data-paginationLoop='true' data-search='true'>";
//  echo "<table
//   id='table'
//   data-toggle='table'
//   data-height='400'
//   data-pagination='true'>";

echo "<thead>";


// echo "<table border='1'>";
// echo "<tr>";
echo "<th data-sortable='true'>Project</th>";
echo "<th data-sortable='true'>Organism</th>";
echo "<th data-sortable='true'>Source</th>";
echo "<th data-sortable='true'>n_samples</th>";
echo "<th data-sortable='true'>study_title</th>";
// echo "<th data-sortable='true'>study_abstract</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
// for loop to present each row
for($j = 0 ; $j < $rows ; ++$j)
    {
        $row = mysql_fetch_row($result);
        echo "<tr>\n";
        echo "<td><a href='project_detail.php?id=$row[0]'>$row[0]</a></td>\n";
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        // echo "<td>$row[5]</td>\n";
    }
echo "</tbody>";
echo "</table>";
?>
