<?php
// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

$sql = '
SELECT a.drwtNo1
     , a.drwtNo2
     , a.drwtNo3
     , a.drwtNo4
     , a.drwtNo5
     , a.drwtNo6
     , b.have
     , count(b.have) as rnkcnt
     , sum(case b.rnk when 1 then 1 else 0 end) rnk1
     , sum(case b.rnk when 2 then 1 else 0 end) rnk2
     , sum(case b.rnk when 3 then 1 else 0 end) rnk3
     , sum(case b.rnk when 4 then 1 else 0 end) rnk4
     , sum(case b.rnk when 5 then 1 else 0 end) rnk5
  FROM have a
     , "rnk" b
 where b.have = a.no
 GROUP by b.have
';

$result = $db->query($sql) or die($PHP_SELF.' line '.__LINE__.', SQL : '.$sql);
?>
<!DOCTYPE HTML>
<html lang='ko' dir='ltr' class='chrome chrome92'>
 <meta charset="utf-8" />
 <meta name="robots" content="noindex,nofollow" />
 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
 <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
 <head>
  <title>No. Rank</title>
  <style>
   table {
    border:1px #666 solid;
    border-spacing:0 0;
    border-collapse:collapse;
    padding:0;
   }
   tr:nth-child(even) {background: #CCC}
   tr:nth-child(odd) {background: #FFF}
   td {
    text-align:center;
    font-size:12px;
   }
   body {margin:0;}
  </style>
 </head>
 <body>
  <table width=100%>
  <tr>
   <td>
 	<table border=1 id='list' width=100% class="col1-right table table-dark table-bordered table-striped table-hover">
	<tr>
		<th>No.</th>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
		<th>6</th>
		<th>당첨수</th>
		<th>1등</th>
		<th>2등</th>
		<th>3등</th>
		<th>4등</th>
		<th>5등</th>
	</tr>
<?php
	//가상번호를 정함
	$loop_number=$total-($page-1)*$page_num;

	// 뽑혀진 데이타만큼 출력함
	while($row = $result->fetchArray(SQLITE3_ASSOC)){         
		$have    = $row['have'   ];
		$drwtNo1 = $row['drwtNo1'];
		$drwtNo2 = $row['drwtNo2'];
		$drwtNo3 = $row['drwtNo3'];
		$drwtNo4 = $row['drwtNo4'];
		$drwtNo5 = $row['drwtNo5'];
		$drwtNo6 = $row['drwtNo6'];
        $rnkCnt  = $row['rnkcnt' ];
		$rank1   = $row['rnk1'   ]?$row['rnk1']:'';
		$rank2   = $row['rnk2'   ]?$row['rnk2']:'';
		$rank3   = $row['rnk3'   ]?$row['rnk3']:'';
		$rank4   = $row['rnk4'   ]?$row['rnk4']:'';
		$rank5   = $row['rnk5'   ]?$row['rnk5']:'';
		?>
		<tr>
			<td><?=$have?></td>
			<td><?=$drwtNo1?></td>
			<td><?=$drwtNo2?></td>
			<td><?=$drwtNo3?></td>
			<td><?=$drwtNo4?></td>
			<td><?=$drwtNo5?></td>
			<td><?=$drwtNo6?></td>
			<td><?=$rnkCnt?></td>
			<td><?=$rank1?></td>
			<td><?=$rank2?></td>
			<td><?=$rank3?></td>
			<td><?=$rank4?></td>
			<td><?=$rank5?></td>
		</tr>
		<?php
		$loop_number--;
	}
?>
    </table>
   </td>
  </tr>
 </table><br>
        <a href="history.php">History</a> | 
        <a href="win.php">당첨</a> | 
        <a href="index.html">Index</a>

 </body>
</html>

<?php
$db->close();
exit();

?>