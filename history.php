<?php
// 페이지번호
$page = $_GET['page'];

// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

$sql = 'SELECT MAX(drwNo) as maxDrwNo FROM "history" WHERE 1';
$data = $db->querySingle($sql, true) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);
$total = $data['maxDrwNo'];

// 페이지 처리
$page_num=25;

$total_page=(int)(($total-1)/$page_num)+1; // 전체 페이지 구함

// 첫페이지 설정
if(!$keyword && !$page) $page=mt_rand(1,$total_page); // 처음 접속시 첫페이지 렌덤 생성
if(!$page) $page=1; // 만약 $page라는 변수에 값이 없으면 임의로 1 페이지 입력

if($page>$total_page) $page=$total_page; // 페이지가 전체 페이지보다 크면 페이지 번호 바꿈

$start_num=($page-1)*$page_num; // 페이지 수에 따른 출력시 첫번째가 될 글의 번호 구함

$print_page="";
$show_page_num=10; // 한번에 보일 페이지 갯수
$start_page=(int)(($page-1)/$show_page_num)*$show_page_num;
$i=1;

$a_1_prev_page= "<Hyunee ";
$a_1_next_page= "<Hyunee ";
$a_prev_page = "<Hyunee ";
$a_next_page = "<Hyunee ";

if($page>1) $a_1_prev_page="<a onfocus=blur() href='$PHP_SELF?page=".($page-1)."&keyword=$keyword'>";
if($page<$total_page) $a_1_next_page="<a onfocus=blur() href='$PHP_SELF?page=".($page+1)."&keyword=$keyword'>";

if($page>$show_page_num) {
	$prev_page=$start_page;
	$a_prev_page="<a onfocus=blur() href='$PHP_SELF?page=$prev_page&keyword=$keyword'>";
	$print_page.="<a onfocus=blur() href='$PHP_SELF?page=1&keyword=$keyword'>1</a>...";
}

while($i+$start_page<=$total_page&&$i<=$show_page_num) {
	$move_page=$i+$start_page;
//	if($page==$move_page) $print_page.=" <b>$move_page</b> ";
	if($page==$move_page) $print_page.=" <a class=selected>$move_page</a> ";
	else $print_page.="<a onfocus=blur() href='$PHP_SELF?page=$move_page&keyword=$keyword'>$move_page</a> ";
	$i++;
}

if($total_page>$move_page) {
	$next_page=$move_page+1;
	$a_next_page="<a onfocus=blur() href='$PHP_SELF?page=$next_page&keyword=$keyword'>";
	$print_page.="...<a onfocus=blur() href='$PHP_SELF?page=$total_page&keyword=$keyword'>$total_page</a>";
}

// 화면에 표시할 목록 구함.
#$sql = "
#SELECT drwNo, drwNoDate, drwtNo1, drwtNo2, drwtNo3, drwtNo4, drwtNo5, drwtNo6, bnusNo
#  FROM history
# ORDER BY drwNo desc
# LIMIT $start_num, $page_num
#";
$sql="
SELECT drwNo
     , drwNoDate
     , drwtNo1
     , drwtNo2
     , drwtNo3
     , drwtNo4
     , drwtNo5
     , drwtNo6
     , bnusNo
     , SUM(CASE rnk WHEN 1 THEN rnkCnt ELSE 0 END) rank1
     , SUM(CASE rnk WHEN 2 THEN rnkCnt ELSE 0 END) rank2
     , SUM(CASE rnk WHEN 3 THEN rnkCnt ELSE 0 END) rank3
     , SUM(CASE rnk WHEN 4 THEN rnkCnt ELSE 0 END) rank4
     , SUM(CASE rnk WHEN 5 THEN rnkCnt ELSE 0 END) rank5
  FROM (
         SELECT a.drwNo
              , a.drwNoDate
              , a.drwtNo1
              , a.drwtNo2
              , a.drwtNo3
              , a.drwtNo4
              , a.drwtNo5
              , a.drwtNo6
              , a.bnusNo
              , b.rnk
              , count(b.rnk) as rnkCnt
           FROM history a
                LEFT OUTER JOIN rnk b
                ON a.drwNo = b.drwNo
--          WHERE a.drwNo=882
          GROUP BY a.drwNo
              , a.drwNoDate
              , a.drwtNo1
              , a.drwtNo2
              , a.drwtNo3
              , a.drwtNo4
              , a.drwtNo5
              , a.drwtNo6
              , a.bnusNo
              , b.rnk
       )
 GROUP BY drwNo
     , drwNoDate
     , drwtNo1
     , drwtNo2
     , drwtNo3
     , drwtNo4
     , drwtNo5
     , drwtNo6
     , bnusNo
 ORDER BY drwNo DESC
 LIMIT $start_num, $page_num
";
$result = $db->query($sql) or die($PHP_SELF.' line '.__LINE__.', SQL : '.$sql);
?>
<!DOCTYPE HTML>
<html lang='ko' dir='ltr' class='chrome chrome92'>
 <meta charset="utf-8" />
 <meta name="robots" content="noindex,nofollow" />
 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
 <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
 <head>
  <title>History</title>
  <style>
   a {
    /*
    width:60px;
    border:1px gray solid;
    */
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: perspective(1px) translateZ(0);
    transform: perspective(1px) translateZ(0);
    box-shadow: 0 0 1px rgb(0 0 0 / 0%);
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;

    margin: 1px 1px 0 0;
    padding: 4px 8px 3px 8px;
    cursor: pointer;
    background: #e1e1e1;
    text-decoration: none;
    color: #666;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
   }
   a.selected {color:#bbb;}
   td {
    text-align:center;
	font-size:12px;
   }
   table {
    border:1px #666 solid;
    border-spacing:0 0;
    border-collapse:collapse;
    padding:0;
   }
   tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
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
		<th>Date</th>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
		<th>6</th>
		<th>Bonus</th>
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
		$number= number_format($loop_number);
		$drwNo     = $row['drwNo'    ];
        $drwNoDate = $row['drwNoDate'];
		$drwtNo1   = $row['drwtNo1'  ];
		$drwtNo2   = $row['drwtNo2'  ];
		$drwtNo3   = $row['drwtNo3'  ];
		$drwtNo4   = $row['drwtNo4'  ];
		$drwtNo5   = $row['drwtNo5'  ];
		$drwtNo6   = $row['drwtNo6'  ];
		$bnusNo    = $row['bnusNo'   ];
		$rank1     = $row['rank1'    ]?$row['rank1'    ]:'';
		$rank2     = $row['rank2'    ]?$row['rank2'    ]:'';
		$rank3     = $row['rank3'    ]?$row['rank3'    ]:'';
		$rank4     = $row['rank4'    ]?$row['rank4'    ]:'';
		$rank5     = $row['rank5'    ]?$row['rank5'    ]:'';
		?>
		<tr>
			<td><a href="win.php?drwNo=<?=$drwNo?>"><?=$number?></font></a></td>
			<td><?=$drwNoDate?></td>
			<td><?=$drwtNo1?></td>
			<td><?=$drwtNo2?></td>
			<td><?=$drwtNo3?></td>
			<td><?=$drwtNo4?></td>
			<td><?=$drwtNo5?></td>
			<td><?=$drwtNo6?></td>
			<td><?=$bnusNo?></td>
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
  <tr>
   <td align=center>
	<?=$a_1_prev_page?>이전</a>
	<?=$a_prev_page?>《</a>
	<?=$print_page?></a>
	<?=$a_next_page?>》</a>
	<?=$a_1_next_page?>다음</a>
   </td>
  </tr>
 </table><br>
        <a href="index.html">Index</a> | 
        <a href="win.php">당첨</a> | 
        <a href="have_rank.php">보유번호 당첨수</a>

 </body>
</html>

<?php
$db->close();
exit();

?>