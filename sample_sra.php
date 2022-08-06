<?php

session_start();
include 'menu.php';
require_once 'page.php';
$db_hostname = 'localhost';
$db_database = 'ceibio_wp775';
$db_username = 'ceibio_wp775';
$db_password = 'RNAlab2022';
echo <<<_HEAD1
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<style>
.button1 {
    font: bold 15px Arial;
    text-decoration: none;
    background-color: #EEEEEE;
    color: #333333;
    padding: 3px 8px 3px 8px;
    border-top: 3px solid #CCCCCC;
    border-right: 3px solid #333333;
    border-bottom: 3px solid #333333;
    border-left: 3px solid #CCCCCC;
    float: right
  }
</style>
_HEAD1;
// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
// throw error message
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$page = isset($_GET['page'])  ? $_GET['page']  : 1 ;

echo "<input type='hidden' name='page' value='{$page }'> ";

echo <<<_TABLE1

  <table  class="table table-bordered">
  <thead id="head-table">
    <tr>
        <th>SampleID</th>
        <th>Project ID</th>
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
  <tbody>
_TABLE1;
echo <<< _SEARCH
<br>
<h2>Search keyword</h2>
<!--  The code below is used to generate a search box
    referencing online resource from
    https://www.sololearn.com/Discuss/27950/how-get-value-button-press
-->
<form action="sample_sra.php" method="GET">
    <!--dropdown list for selecting query attribute-->
    <select name="text">
        <option value="0">--Select field--</option>
        <option value="all">All field</option>
        <option value="sampleID">Sample ID</option>
        <option value="projectID">Project ID</option>
        <option value="tissue">Tissue</option>
        <option value="cellline">Cell line</option>
        <option value="celltype">Cell type</option>
        <option value="samplecondition">Condition</option>
        <option value="treatment">Treatment</option>
        <option value="treatment_duration">Treatment duration</option>
        <option value="genotype">Genotype</option>
        <option value="subtype">Subtype</option>
    </select>
    <input type="text" name="keyword" class="button"/>
    <br>
    <br>
    <input type="submit" name="submit" value="Confirm" />
</form>


_SEARCH;
$p = new Page();
// pagination
// order by project ID
$orderBy = isset($_GET['order']) ? $_GET['order'] : 'sampleID';
// check input keyword

if(isset($_GET['submit']) != ''){
    # get selected attribute
    $selected_val = $_GET['text'];  
    #echo $selected_val;
    #echo "<br>";
    # get input value
    $input_keyword = $_GET['keyword'];
    # echo $input_keyword;
    # connect to database
    $db_server = mysql_connect($db_hostname,$db_username,$db_password);
    if(!$db_server) die("Unable to connect to database: " . mysql_error());
    mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
    if($selected_val=='all'){
        $query = "select * from sample where ";
        $count = "select count(*) from sample where ";
        $sql_search_fields = Array();
        $sql = "SHOW COLUMNS FROM sample";
        $rs = mysql_query($sql);
        while ($r = mysql_fetch_array($rs)) {
            $colum = $r[0];
            $sql_search_fields[] = $colum . " like('%" . $input_keyword . "%')";
        }
        $query .= implode(" OR ", $sql_search_fields) . $p->limit();
        $count .= implode(" OR ", $sql_search_fields);
    }else{
        # construct query command
        $query = "select * from sample where ".$selected_val."  LIKE '%".$input_keyword."%' ". $p->limit();
        $count = "select count(*) from sample where ".$selected_val."  LIKE '%".$input_keyword."%'";
        // $result = mysql_query($query);
        $_SESSION['supmask'] = $smask;
    }
}else{
    $query = "SELECT * FROM sample " . $p->limit();
    $count = "SELECT count(*) FROM sample";
}

echo "<br><br><a href='sra_download.php?comm=".urlencode(str_replace('LIMIT 0 ,3','',$query))."' class='button1'>Download query result</a>";
echo "<br><br>";

// echo $mysql_query($count);
$p->setCount(mysql_query($count));
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
// for loop to present each row
for($j = 0 ; $j < $rows ; ++$j)
    {
        $row = mysql_fetch_row($result);
        // echo $row;
        echo "<tr>\n";
        echo "<th><a href='Sample_detail.php?id=$row[0]'>$row[0]</a></th>\n";
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[5]</td>\n";
        echo "<td>$row[6]</td>\n";
        echo "<td>$row[7]</td>\n";
        echo "<td>$row[8]</td>\n";
        echo "<td>$row[9]</td>\n";
        echo "<tr>\n";
    }
echo "</tbody>";
echo "</table>";
echo "</tbody></table>";
echo  $p->showPages() .  "</div></div>";

?>
<script>
<script>
 $(function () {
   $('#head-table').find('th').click(function() {
     var type = $(this).attr('data-type')
     location.href = '/s2172876/motif/sample_sra.php?order=' + type
   })
 })
</script>
