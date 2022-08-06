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
<style>
td {
    max-width: 350px !important;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
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

  <table class="table table-bordered">
  <thead id="head-table">
    <tr>
        <th>Motif</th>
        <th>Type</th>
        <th>Organism</th>
        <th>Count</th>
        <th>Contrast ID</th>
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
<p>Select a field and enter keywords to search.</p>
<form action="Motif.php" method="GET">
    <!--dropdown list for selecting query attribute-->
    <select name="text">
        <option value="0">--Select field--</option>
        <option value="motif">Motif</option>
        <option value="ExpID">Contrast ID</option>
        <option value="count">Count</option>
    </select>
    <input type="text" name="keyword" class="button"/>
    <br><br>
    <p>Select a field and limit the sample size range (Optional).</p>

    greater than <input type="text" name="min" class="button"/>
    less than<input type="text" name="max" class="button"/>
    <br> 
    <input type="submit" name="submit" value="Confirm"/>
</form>

_SEARCH;

$p = new Page();
// pagination
// order by project ID
$orderBy = isset($_GET['order']) ? $_GET['order'] : 'ExpID';

// check input keyword
if(isset($_GET['submit']) != ''){
    # get selected attribute
    $selected_val = '';
    $input_keyword = '';
    if(isset($_GET['text'])!=''){
        $selected_val = $_GET['text']; 
    }
    # get input value
    if(isset($_GET['keyword'])!=''){
        $input_keyword = $_GET['keyword']; 
    }

    $min = '';
    $max = '';

    if(isset($_GET['min'])){
        $min = $_GET['min'];
    }
    if(isset($_GET['max'])){
        $max = $_GET['max'];
    }

    $db_server = mysql_connect($db_hostname,$db_username,$db_password);
    if(!$db_server) die("Unable to connect to database: " . mysql_error());
    mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
    if($input_keyword!='' && $min=='' && $max==''){
        if($selected_val=='all'){
            $query = "select * from motif where ";
            $count = "select count(*) from motif where ";
            $sql_search_fields = Array();
            $sql = "SHOW COLUMNS FROM motif";
            $rs = mysql_query($sql);
            while ($r = mysql_fetch_array($rs)) {
                $colum = $r[0];
                $sql_search_fields[] = $colum . " like('%" . $input_keyword . "%')";
            }
            $query .= implode(" OR ", $sql_search_fields) . $p->limit();
            $count .= implode(" OR ", $sql_search_fields);
        }else{
            # construct query command
            $query = "select * from motif where ".$selected_val."  LIKE '%".$input_keyword."%' ". $p->limit();
            $count = "select count(*) from motif where ".$selected_val."  LIKE '%".$input_keyword."%' ";
            // $result = mysql_query($query);
            $_SESSION['supmask'] = $smask;
            }
            echo "<br>The following table presents the comparisons with the keyword '".$input_keyword."'";
        }

    }
    if($input_keyword!='' && $min!='' && $max!=''){
        if($selected_val=='all'){
            $query = "select * from motif where count > ".$min." AND count < ".$max." AND ";
            $count = "select count(*) from motif where count > ".$min." AND count < ".$max." AND ";
            $sql_search_fields = Array();
            $sql = "SHOW COLUMNS FROM motif";
            $rs = mysql_query($sql);
            while ($r = mysql_fetch_array($rs)) {
                $colum = $r[0];
                $sql_search_fields[] = $colum . " like('%" . $input_keyword . "%' )";
            }
            $query .= implode(" OR ", $sql_search_fields) . $p->limit();
            $count .= implode(" OR ", $sql_search_fields);
        }else{
            # construct query command
            $query = "select * from motif where ".$selected_val."  LIKE '%".$input_keyword."%' AND count > ".$min." AND count < ".$max." ". $p->limit();
            $count = "select count(*) from motif where ".$selected_val."  LIKE '%".$input_keyword."%' AND count > ".$min." AND count < ".$max;
            // $result = mysql_query($query);
            $_SESSION['supmask'] = $smask;
            }
            echo "<br>The following table presents the ontrasts with the keyword '".$input_keyword."' and the number of ".selected_numfield." between ".$min." and ".$max;
    }
    if($input_keyword=='' && $min!=''  && $max!=''){
        $query = "select * from motif where count > ".$min." AND count < ".$max." ". $p->limit();
        $count = "select count(*) from motif where count > ".$min." AND count < ".$max;
        echo "<br>The following table presents the contrasts with the number of count between ".$min." and ".$max;
    }
    if(($min=='' && $max!='') || ($min!='' && $max=='')){
        echo "Please fill in both the maximum and minimum values";
    }
    if($selected_val == '0' && $input_keyword!=''){
        echo "<br>Please select valid field for keyword search";
    }
    if($max=='' && $min=='' && $input_keyword==''){
        echo "<br>Please select valid field to limit sample size";
    }

if($input_keyword=='' && $min=='' && $max==''){
        $query = "SELECT * FROM motif " . $p->limit();
        $count = "SELECT count(*) FROM motif ";
}

echo "<br><br><a href='motif_download.php?comm=".urlencode(str_replace('LIMIT 0 ,3','',$query))."' class='button1'>Download query result</a>";
echo "<br><br>";
// echo $query;
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
        echo "<td> <a href='motif_detail.php?id=$row[0] & type=$row[1] & orgn=$row[2]'>$row[0]</a></th>\n";
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<tr>\n";
    }
echo "</tbody>";
echo "</table>";
echo "</tbody></table>";
echo  $p->showPages() .  "</div></div>";
?>
