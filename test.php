echo <<<_TAIL2

  <button class="accordion">Motif</button>
  <div class="panel">
  </div>
  <button class="accordion">Samples</button>
  <div class="panel">
  <table border="1">
    <tr>
    <th>Comparison condition</th> 
    <th>Sample IDs</th> 
    </tr>
  </body>
  </html>
_TAIL2;
if($setpar) {
    // define query sentence
    $compsel = "select * from DEGSummary where ExpID='".$_GET['id']."';";
    // query database
    $result = mysql_query($compsel);
    if(!result) die("unable to process query: " . mysql_error());
    $row = mysql_fetch_row($result);
    for($j = 0 ; $j < 2 ; ++$j){
        $row1 = mysql_fetch_row($result1);
        echo "<tr>\n";
        echo "<td>";
        if(explode(" vs ",$row[2])[$j] == ''){
          echo "Control";
        }else{
          print_r(explode(" vs ",$row[2])[$j]);
        }
        echo "</td>\n";
        echo "<td>";
        $str_arr = explode (",", $row[$j+7]);
        for($k = 0 ; $k < count($str_arr); ++$k){
            echo "<a href='Sample_detail.php?id=$str_arr[$k]'>";
            echo $str_arr[$k] ;
            if($k+1 !=count($str_arr)){
                echo ',';
            }  
        };
        echo "</td>\n";
        echo "</tr>\n";
      }
    // echo $row[2];
    // print_r(explode(" vs ",$row[2])[0]);
  }