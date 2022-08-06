<?php
// refer to https://iamkate.com/code/creating-csv-downloads-in-php/

$db_hostname = 'localhost';
$db_database = 'ceibio_wp775';
$db_username = 'ceibio_wp775';
$db_password = 'RNAlab2022';

if(isset($_GET['comm'])) {
    $query = $_GET['comm'];
  }

header('Content-Type: text/txt; charset=utf-8');
header('Content-Disposition: attachment; filename=query.txt');

$output = fopen('php://output', 'w');
fputcsv($output, ['ProjectID', 'Sample ID', 'Project name', 'Tissue', 'Tumor status','Pathological staging (N)','Clinical staging (M)','Clinical stage','Pathological stage','Pathological staging (T)','Histological type','Submitter ID']);

$connection = new mysqli('localhost', 'ceibio_wp775', 'RNAlab2022', 'ceibio_wp775');
$rows = $connection->query(
    $query,
  MYSQLI_USE_RESULT
);
while ($row = $rows->fetch_row()) {
  fputcsv($output, $row);
}

fclose($output);
mysqli_free_result($result);
mysqli_close($db);
?>
