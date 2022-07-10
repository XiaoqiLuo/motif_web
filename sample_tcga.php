<?php

session_start();
include 'menu.php';
require_once 'page.php';
$db_hostname = 'localhost';
$db_database = 's2172876';
$db_username = 's2172876';
$db_password = '123456';
echo <<<_HEAD1
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
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
  <tbody>
_TABLE1;
echo <<< _SEARCH
<br>
<h2>Search keyword</h2>
<!--  The code below is used to generate a search box
    referencing online resource from
    https://www.sololearn.com/Discuss/27950/how-get-value-button-press
-->
<form action="sample_tcga.php" method="GET">
    <!--dropdown list for selecting query attribute-->
    <select name="text">
        <option value="0">--Select field--</option>
        <option value="all">All field</option>
        <option value="study">Project ID</option>
        <option value="external_id">Sample ID</option>
        <option value="project_name">Project name</option>
        <option value="sample_type">Tissue</option>
        <option value="tumor_stage">Tumor stage</option>
        <option value="case_vital_status">Vital status</option>
        <option value="pathologic_n">Pathological staging (N)</option>
        <option value="clinical_stage">Clinical stage</option>
        <option value="pathologic_stage">Pathological stage</option>
        <option value="pathologic_t">Pathological staging (T)</option>
        <option value="histological_type">Histological type</option>
        <option value="submitter_id">Submitter ID</option>
    </select>
    <input type="text" name="keyword" class="button"/>
    <br>
    <br>
    <input type="submit" name="submit" value="Set" />
</form>


_SEARCH;
$p = new Page();
// pagination
// order by project ID
$orderBy = isset($_GET['order']) ? $_GET['order'] : 'study';
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
        $query = "select * from TCGAannotation where ";
        $count = "select count(*) from TCGAannotation where ";
        $sql_search_fields = Array();
        $sql = "SHOW COLUMNS FROM TCGAannotation";
        $rs = mysql_query($sql);
        while ($r = mysql_fetch_array($rs)) {
            $colum = $r[0];
            $sql_search_fields[] = $colum . " like('%" . $input_keyword . "%')";
        }
        $query .= implode(" OR ", $sql_search_fields) . $p->limit();
        $count .= implode(" OR ", $sql_search_fields);
    }else{
        # construct query command
        $query = "select * from TCGAannotation where ".$selected_val."  LIKE '%".$input_keyword."%' order by {$orderBy} desc ". $p->limit();
        $count = "select count(*) from TCGAannotation where ".$selected_val."  LIKE '%".$input_keyword."%'";
        // $result = mysql_query($query);
        $_SESSION['supmask'] = $smask;
    }
}else{
    $query = "SELECT * FROM TCGAannotation order by {$orderBy} ASC " . $p->limit();
    $count = "SELECT count(*) FROM TCGAannotation";
}

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
        echo "<th>$row[0]</th>\n";
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[5]</td>\n";
        echo "<td>$row[6]</td>\n";
        echo "<td>$row[7]</td>\n";
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
echo "</tbody></table>";
echo  $p->showPages() .  "</div></div>";

?>
<script>
<script>
 $(function () {
   $('#head-table').find('th').click(function() {
     var type = $(this).attr('data-type')
     location.href = '/s2172876/motif/sample_tcga.php?order=' + type
   })
 })
</script>
