<?php
$db_hostname = 'localhost';
$db_database = 's2172876';
$db_username = 's2172876';
$db_password = '123456';
session_start();

echo <<<_HEAD1
<html>
<script src="jquery.min.js"></script>
<style>
.main{
  position: relative;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
</style>
<head>
  <title> Suppliers Information </title>
</head>
<body>
_HEAD1;

// Add the menuf.php (the navigator) at the top of page


/**
 * Log in to the MySQL database.
 * $db_hostname, $db_username, $db_password, $db_database are set in the login.php.
 * @param str $query the query need to be searched in the database
 * @param str $result the search reasult using the "$query"
 * @param int $rows the number of rows in the result
 */
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
// Raise error when the conection failed.
if (!$db_server) die("Unable to connect to database: " . mysql_error());
// Raise error when the unable to select database
mysql_select_db($db_database, $db_server) or die("Unable to select database: " . mysql_error());
// Define the query, select everything from the Manufactures database
$query = "select * from Manufacturers";
// Save the results
$result = mysql_query($query);
// Raise error when failed search the query
if (!$result) die("unable to process query: " . mysql_error());
// Save the number of rows in the results
$rows = mysql_num_rows($result);
$smask = $_SESSION['supmask'];
$manuList = [];
for ($j = 0; $j < $rows; ++$j) {
  // Read the results row by row
  $row = mysql_fetch_row($result);

  $manuList[$row[0]] = $row[1];
  // Store the id in $sid
  $sid[$j] = $row[0];
  // Store the name in $snm
  $snm[$j] = $row[1];
  $sact[$j] = 0;
  $tvl = 1 << ($sid[$j] - 1);
  if ($tvl == ($tvl & $smask)) {
    $sact[$j] = 1;
  }
}
// Check if the check box has set the supplier
if (isset($_POST['supplier'])) {
  // Store the choosen supplier from the previous form in $supplier
  $supplier = $_POST['supplier'];
  // Store the number of the suppliers that were choosen by user
  $nele = sizeof($supplier);
  for ($k = 0; $k < $rows; ++$k) {
    $sact[$k] = 0;
    for ($j = 0; $j < $nele; ++$j) {
      if (strcmp($supplier[$j], $snm[$k]) == 0) $sact[$k] = 1;
    }
  }
  $smask = 0;
  for ($j = 0; $j < $rows; ++$j) {
    if ($sact[$j] == 1) {
      $smask = $smask + (1 << ($sid[$j] - 1));
    }
  }
  $_SESSION['supmask'] = $smask;
}

/**
 * Make a container for user to choose from different manufactures.
 * @param str $snm the manufactures form the SQL database.
 */
echo '<div class="main" style="top: -200px;"><div class="container" style="position: relative; top:300px;display: flex;
justify-content: center;
flex-direction: column;">';

echo '<form action="test.php" method="GET" style="display: -webkit-box;display: flex;flex-wrap: 
  wrap;-webkit-box-orient: vertical;-webkit-box-direction: normal;flex-direction: column;align-items: center;">';
echo '<h2 > Select the manufacturer(s) that you are interseted in. </h2>';
echo '<div>';
// Below is the check box
$page = isset($_GET['page'])  ? $_GET['page']  : 1;
echo "<input type='hidden' name='page' value='{$page }'> ";
foreach ($manuList as $key => $val) {
  if (isset($supplier) && in_array($key, $supplier)) {
    echo "<label> <input type='checkbox' checked name='supplier[]' value='{$key}'";
  } else {
    echo "<label> <input type='checkbox' name='supplier[]' value='{$key}'";
  }
  echo $val;
  echo'"/> <span>';
  echo $val;
  echo'</span> </label>';
}


/**
 * Make two button for user to reset the choices or submit the chossen manufactures (using the provided templete from w3 school).
 */
echo <<<_BODY1
<p>
  <button type="reset" class="w3-button w3-center"> Reset </button>
  <input type="submit" class="w3-button w3-center w3-theme" value="Done" />
</p>
</div>
_BODY1;

/**
 * Show the cuttent manufactures.
 * @param int $sact Whether the user have choosed this manufacture.
 */
if (isset($_GET['supplier'])) {
  echo '<div style="position: relative; top=20px;"> Currently selected Suppliers: ';
  $tmep = [];
  foreach($manuList as $key => $val) {
    if (in_array($key, $_GET['supplier'])) {
      
      $tmep[] = $val;
    }
  }

  echo join(',', $tmep);
  echo "<br>Sort the table by clicking the table head";
  echo "</form></div>";
}
/**
 * Log in to the MySQL database.
 * $db_hostname, $db_username, $db_password, $db_database are set in the login.php.
 */
$db_server = mysql_connect( $db_hostname, $db_username, $db_password );
if ( !$db_server )die( "Unable to connect to database: " . mysql_error() );
mysql_select_db( $db_database, $db_server )or die( "Unable to select database: " . mysql_error() );
/**
 * Add the title for the table.
 * Warped the table under a <div> label with relative position.
 */
echo <<<_TABLE1
  <table class="fl-table">
    <thead id="head-table">
    <tr >
      <th> Suppliers </th>
      <th data-type="id"> id </th>
      <th  data-type="natm"> natm </th>
      <th  data-type="ncar"> ncar </th>
      <th  data-type="nnit"> nnit </th>
      <th data-type="noxy"> noxy </th>
      <th  data-type="nsul"> nsul </th>
      <th  data-type="ncycl"> ncycl </th>
      <th data-type="nhdon"> nhdon </th>
      <th data-type="nhacc"> nhacc </th>
      <th data-type="nrotb"> nrotb </th>
      <th data-type="ManuID"> ManuID </th>
      <th data-type="catn"> catn </th>
      <th data-type="catn"> mw </th>
      <th data-type="TPSA"> TPSA </th>
      <th data-type="XLogP"> XLogP </th>
    </tr>
    </thead>
    <tbody>
_TABLE1;

$p = new Page();


/**
 * Exhibit the information from Compounds database.
 * Show the top 3 result using 'limit 3' in MySQL, so the output won't be too much.
 */
$orderBy = isset($_GET['order']) ? $_GET['order'] : 'id';
if (!empty($supplier)) {
  $inId = join(',', $supplier);
  $query = "SELECT * FROM Compounds WHERE ManuID in ($inId)  order by {$orderBy} desc " . $p->limit();
  $count = "SELECT count(*) FROM Compounds WHERE ManuID in ($inId)";
} else {
  $query = "SELECT * FROM Compounds  order by {$orderBy} desc " . $p->limit();
  $count = "SELECT count(*) FROM Compounds ";
}
echo $query;
// exit;
$p->setCount(mysql_query($count));
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
// $rows = mysql_fetch_row($result);
// echo $sup[$j];
// echo $query;
// echo $result;
// echo $row[1];
while($row = mysql_fetch_row($result)) {
  // echo $row[1];
  // print_r($rows);
  // print_r($rows);
  echo "<tr>";
  echo "<td>";
  echo $manuList[$row[10]];
  echo "</td>";
  echo '<td>' . $row[0] . '</td>';
  echo '<td>' . $row[1] . '</td>';
  echo '<td>' . $row[2] . '</td>';
  echo '<td>' . $row[3] . '</td>';
  echo '<td>' . $row[4] . '</td>';
  echo '<td>' . $row[5] . '</td>';
  echo '<td>' . $row[6] . '</td>';
  echo '<td>' . $row[7] . '</td>';
  echo '<td>' . $row[8] . '</td>';
  echo '<td>' . $row[9] . '</td>';
  echo '<td>' . $row[10] . '</td>';
  echo '<td>' . $row[11] . '</td>';
  echo '<td>' . $row[12] . '</td>';
  echo '<td>' . $row[13] . '</td>';
  echo '<td>' . $row[14] . '</td>';
  echo "</tr>";
  // }
}

echo "</tbody></table>";

  echo  $p->showPages() .  "</div></div>";


// Add a footer at the bottom of this page. Since I used relative position, so this footer can adjust to the previous search result.
// I used 'top:200' to make sure this footer won't connect to the end of previous table.
echo <<<_TAIL1
</body>
<div class="main" style="top: 300;">
  <footer class="w3-container w3-padding-32 w3-theme-d1 w3-center" style="position: relative;">
    <h4> This is the end </h4>
    <p> Thank you </p>
    <p> Thanks w3schools for the website template </p>
  </footer>
</div>
</html>
_TAIL1;

?>
<script>
 $(function () {
   $('#head-table').find('th').click(function() {
     var type = $(this).attr('data-type')
     location.href = '/s2172876/motif/test/test.php?order=' + type
   })
 })</script>