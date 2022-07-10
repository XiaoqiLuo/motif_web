<!-- <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script> -->

<?php
session_start();
include 'menu.php';
require_once 'page.php';
$db_hostname = 'localhost';
$db_database = 's2172876';
$db_username = 's2172876';
$db_password = '123456';
echo<<<_HEAD1
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
_HEAD1;
// connect database
$db_server = mysql_connect($db_hostname,$db_username,$db_password);
// throw error message
if(!$db_server) die("Unable to connect to database: " . mysql_error());
mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());     
$query = "select * from source";
$result = mysql_query($query);
if (!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
$smask = $_SESSION['supmask'];
$manuList = [];

for ($j = 0; $j < $rows; ++$j) {
// fetch result by row
  $row = mysql_fetch_row($result);
// store all source
  $manuList[$row[0]] = $row[1];
  $sid[$j] = $row[0];
  $snm[$j] = $row[1];
  $sact[$j] = 0;
  $tvl = 1 << ($sid[$j] - 1);
  if ($tvl == ($tvl & $smask)) {
    $sact[$j] = 1;
  }
}

// selected or not?
if (isset($_GET['source'])||isset($_GET['maximum'])||isset($_GET['minimum'])) { // if submitted the choice
  $maximum = $_GET['maximum'];
  $minimum = $_GET['minimum'];
  if(($maximum != '') && ($minimum != '')){
    $where_pj_limit = " and n_samples < ".$maximum." and n_samples >".$minimum;
    $where_pj_unlimit = " where n_samples < ".$maximum." and n_samples >".$minimum;
  }
  if(($maximum != '') && ($minimum == '')){
    $where_pj_limit = " and n_samples < ".$maximum;
    $where_pj_unlimit = " where n_samples < ".$maximum;
  }
  if(($maximum == '') && ($minimum != '')){
    $where_pj_limit = " and n_samples >".$minimum;
    $where_pj_unlimit = " where n_samples >".$minimum;
  }
  if(($maximum == '') && ($minimum == '')){
    $where_pj_limit = "";
    $where_pj_unlimit = "";
  }   
  $source = $_POST['source'];
    $nele = sizeof($source);
    for ($k = 0; $k < $rows; ++$k) {
      $sact[$k] = 0;
      for ($j = 0; $j < $nele; ++$j) {
        if (strcmp($source[$j], $snm[$k]) == 0) $sact[$k] = 1;
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
 
echo '<form action="project_human.php" method="GET" >';
echo '<div>';

$page = isset($_GET['page'])  ? $_GET['page']  : 1 ;

echo "<input type='hidden' name='page' value='{$page }'> ";
echo "Check the box to specify the source of the project.";
echo "<br>";
// TCGA,SRA,GTEx checkbox
foreach ($manuList as $key => $val) {
  if (isset($source) && in_array($key, $source)) {
    echo "<label> <input type='checkbox' checked name='source[]' value='{$key}'";
  } else {
    echo "<label> <input type='checkbox' name='source[]' value='{$key}'";
  }
  echo $val;
  echo'"/> <span>';
  echo $val;
  echo '</span> </label>';
}
echo "<br>";
// Max, min input form 
echo "Enter integers to limit the number of samples. You can enter only the maximum or minimum, or both.";
echo "<br>";
echo "<form enctype=\"multipart/form-data\" action=\" \" method=\"post\">
Maximum sample size<input name=\"maximum\" type=\"text\"/><br>
Minimum sample size<input name=\"minimum\" type=\"text\"/>
";
echo "<p>  <input type='submit' value='Done' /></p></div></form>";
echo <<<_TAIL1
</body>
</html>
_TAIL1;
$all_source = array();

if (isset($_GET['source'])) {
    $tmep = [];
    foreach($manuList as $key => $val) {
      if (in_array($key, $_GET['source'])) {
        $tmep[] = $val;
        array_push($all_source, $val);
      }
    }
    echo "</form></div>";
  }
// print_r($all_source);

// table
// start of the table content
echo <<<_TABLE1

  <table class="table table-bordered">
  <thead id="head-table">
    <tr>
      <th data-type='projectID'> projectID </th>
      <th data-type='organism'> organism </th>
      <th data-type='project_home'> project_home </th>
      <th data-type='n_samples'> n_samples </th>
      <th data-type='study_title'> study_title </th>
      <th data-type='study_abstract'> study_abstract </th>

    </tr>
    </thead>
    <tbody>
_TABLE1;

$p = new Page();
// pagination
// order by project ID
$orderBy = isset($_GET['order']) ? $_GET['order'] : 'projectID';

if (!empty($all_source)) {
  $all_source = implode("','",$all_source);
  $query = "SELECT * FROM project where organism='human' and project_home in ('".$all_source."')".$where_pj_limit." order by {$orderBy} ASC " . $p->limit();
  $count = "SELECT count(*) FROM project where organism='human' and project_home  in ('".$all_source."') ".$where_pj_limit;

} else {
  if( $_GET['minimum']=='' && $_GET['maximum']==''){
    $query = "SELECT * FROM project ".$where_pj_unlimit." where organism = 'human' order by {$orderBy} ASC " . $p->limit();
    $count = "SELECT count(*) FROM project ".$where_pj_unlimit." where organism = 'human'";
  }else{
    $query = "SELECT * FROM project ".$where_pj_unlimit." and organism = 'human' order by {$orderBy} ASC " . $p->limit();
    $count = "SELECT count(*) FROM project ".$where_pj_unlimit." and organism = 'human'";
  }
  
}
// echo $query;
echo "You are browsing projects (organism:human) from ";
if($all_source){
  echo str_replace('\'', "", $all_source);
}else{
  echo "SRA,TCGA,GTEx";
}
if( $_GET['minimum'] != '' && $_GET['maximum'] !=''){
  echo " with sample size greater than ".$_GET['minimum']." and less than ".$_GET['maximum'];
}
if( $_GET['minimum'] != '' && $_GET['maximum'] ==''){
  echo " with sample size greater than ".$_GET['minimum'];
}
if( $_GET['minimum'] == '' && $_GET['maximum'] !=''){
  echo " with sample size less than ".$_GET['maximum'];
}

// echo $mysql_query($count);
$p->setCount(mysql_query($count));
$result = mysql_query($query);

// for loop to present each row
for($j = 0 ; $j < $rows ; ++$j)
    {
        $row = mysql_fetch_row($result);
        echo "<tr>\n";
        echo "<th><a href='project_detail.php?id=$row[0]'>$row[0]</a></td>\n";
        echo "<td>$row[1]</td>\n";
        echo "<td>$row[2]</td>\n";
        echo "<td>$row[3]</td>\n";
        echo "<td>$row[4]</td>\n";
        echo "<td>$row[5]</td>\n";
        echo "</tr>\n";
    }
echo "</tbody>";
echo "</table>";
echo "</tbody></table>";
echo  $p->showPages() .  "</div></div>";

?>
<script>
 $(function () {
   $('#head-table').find('th').click(function() {
     var type = $(this).attr('data-type')
     location.href = '/s2172876/motif/project_human.php?order=' + type
   })
 })
</script>
