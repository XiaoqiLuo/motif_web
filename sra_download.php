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
fputcsv($output, ['SampleID', 'ProjectID', 'Tissue', 'Cell line', 'Cell type', 'Condition','Treatment','Treatment duration','Genotype','Subtype']);

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
