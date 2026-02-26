<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
<?php
// 회차번호
$drwNo = $_GET['drwNo'];
if(!$drwNo) $drwNo=1;

// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

$sql = 'SELECT * FROM "history" WHERE drwNo='.$drwNo;
$data = $db->querySingle($sql, true) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);

// 보유번호 목록의 당첨여부 확인(각 번호 당첨 여부 추가 1:당첨 2:보너스번호당첨)
$sql = "
select c.no       as no
     , c.paper    as paper
     , c.drwtNo1  as drwtNo1
     , case c.drwtNo1 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo1
     , c.drwtNo2  as drwtNo2
     , case c.drwtNo2 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo2
     , c.drwtNo3  as drwtNo3
     , case c.drwtNo3 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo3
     , c.drwtNo4  as drwtNo4
     , case c.drwtNo4 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo4
     , c.drwtNo5  as drwtNo5
     , case c.drwtNo5 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo5
     , c.drwtNo6  as drwtNo6
     , case c.drwtNo6 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo6
     , case d.cnt when 3 then 5
                  when 4 then 4
                  when 5 then case e.bnusNo when c.drwtNo1 then 2
                                            when c.drwtNo2 then 2
                                            when c.drwtNo3 then 2
                                            when c.drwtNo4 then 2
                                            when c.drwtNo5 then 2
                                            when c.drwtNo6 then 2
                              else 3 end
                  when 6 then 1
       else '' end as rank
  from have c left outer join
       (
        select no, cnt
          from (
                select a.no, count(a.no) as cnt
                  from have a
                     , (select drwtNo1 as drwtNo from history where drwNo=$drwNo
                        union all
                        select drwtNo2 as drwtNo from history where drwNo=$drwNo
                        union all
                        select drwtNo3 as drwtNo from history where drwNo=$drwNo
                        union all
                        select drwtNo4 as drwtNo from history where drwNo=$drwNo
                        union all
                        select drwtNo5 as drwtNo from history where drwNo=$drwNo
                        union all
                        select drwtNo6 as drwtNo from history where drwNo=$drwNo) b
                 where a.use=1
                   and (a.drwtNo1 = b.drwtNo
                        or a.drwtNo2 = b.drwtNo
                        or a.drwtNo3 = b.drwtNo
                        or a.drwtNo4 = b.drwtNo
                        or a.drwtNo5 = b.drwtNo
                        or a.drwtNo6 = b.drwtNo)
                 group by a.no
                 order by a.no asc
               )
         where cnt>2
       ) d on c.no=d.no
     , (select drwtNo1, drwtNo2, drwtNo3, drwtNo4, drwtNo5, drwtNo6, bnusNo from history where drwNo=$drwNo) e
";

#$json_data = array();

// 뽑혀진 데이타만큼 출력함
//while($row = $result->fetchArray(SQLITE3_ASSOC)){ $json_data[]=$row;
//echo json_encode($json_data);

//{"no":4,"paper":1
//,"drwtNo1":7,"isDrwtNo1":"","drwtNo2":12,"isDrwtNo2":"","drwtNo3":29,"isDrwtNo3":"","drwtNo4":30,"isDrwtNo4":"","drwtNo5":33,"isDrwtNo5":"","drwtNo6":35,"isDrwtNo6":"","rank":""}
?>
<style>
 table {
  border:1px #666 solid;
  border-spacing:0 0;
  border-collapse:collapse;
  padding:0;
 }
 td{
  font-size:13px;
  width:20px;
  text-align:center;
//  background-color:#666;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
 }
 td.bingo {background-color:#ccc;}
 td.bingo1{background-color:#ff9999;}
 td.bingo2{background-color:#660066;}
 td.bingo3{background-color:#69c8f0;}
 td.bingo4{background-color:#ff7272;}
 td.bingo5{background-color:#b0d840;}
 div{width:100%;}
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
  body {margin:0;}
  span.ball{
   background: #69c8f2;
   text-shadow: 0px 0px 3px rgb(0 49 70 / 80%);
   width: 22px;
   height: 22px;
   line-height: 22px;
   font-size: 14px;
   display: inline-block;
   border-radius: 100%;
   text-align: center;
   vertical-align: middle;
   color: #fff;
   font-weight: 500;
   text-align:center;
//   background-color:#ffcc33;
//   -webkit-border-radius: 8px;
//   -moz-border-radius: 8px;
  }
</style>
<?php
echo '<table width=100%><tr><td>';
echo '<div align=center>';
//print_r($data);
echo $data['drwNo'].'회차 / ';
echo $data['drwNoDate'];
echo ' / ';
echo '<span class=ball>'.$data['drwtNo1'].'</span>';
echo '<span class=ball>'.$data['drwtNo2'].'</span>';
echo '<span class=ball>'.$data['drwtNo3'].'</span>';
echo '<span class=ball>'.$data['drwtNo4'].'</span>';
echo '<span class=ball>'.$data['drwtNo5'].'</span>';
echo '<span class=ball>'.$data['drwtNo6'].'</span>';
echo ' + ';
echo '<span class=ball>'.$data['bnusNo' ].'</span>';
echo '</div>';
echo '</td></tr><tr><td>';

$result = $db->query($sql) or die($PHP_SELF.' line '.__LINE__.', SQL : '.$sql);

// 뽑혀진 데이타만큼 출력함
$paper=0;
while($row = $result->fetchArray(SQLITE3_ASSOC)){
	if($paper!=$row['paper']){
		if($paper) echo '</table>';
		$paper=$row['paper'];
		echo '<table align=left>';
	}
	echo '<tr>';
//	echo '<tr class=rank'.$row['rank'].'>';
	echo  '<td'.($row['isDrwtNo1']?' class=bingo'.$row['rank']:'').'>'.$row['drwtNo1'].'</td>';
	echo  '<td'.($row['isDrwtNo2']?' class=bingo'.$row['rank']:'').'>'.$row['drwtNo2'].'</td>';
	echo  '<td'.($row['isDrwtNo3']?' class=bingo'.$row['rank']:'').'>'.$row['drwtNo3'].'</td>';
	echo  '<td'.($row['isDrwtNo4']?' class=bingo'.$row['rank']:'').'>'.$row['drwtNo4'].'</td>';
	echo  '<td'.($row['isDrwtNo5']?' class=bingo'.$row['rank']:'').'>'.$row['drwtNo5'].'</td>';
	echo  '<td'.($row['isDrwtNo6']?' class=bingo'.$row['rank']:'').'>'.$row['drwtNo6'].'</td>';
	echo '</tr>';
}
echo '</table></td></tr></table>';

$sql = 'SELECT MAX(drwNo) as maxDrwNo FROM "history" WHERE 1';
$data = $db->querySingle($sql, true) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);
$maxDrwNo = $data['maxDrwNo'];

echo '<div align=center>';
$b = $drwNo-1;
//if($b==0) $b=1;
$prev = 'href='.$PHP_SELF.'?drwNo='.$b;
//if($drwNo==$b) $before='';
if($b==0) $prev='href=javascript:;';
echo  "<a class=prev $prev>이전</a>";

$bb = $drwNo-10;
$a_prev = 'href='.$PHP_SELF.'?drwNo='.$bb;
//if($drwNo==$b) $before='';
//if($bb<=0) $a_prev='href='.$PHP_SELF.'?drwNo=1';
if($bb<=0) $a_prev='href=javascript:;';
echo  "<a class=prev $a_prev>《</a>";

$weit = 5;
$start = $drwNo-$weit;
if($start<1) $start=1;
$end   = $drwNo+$weit;
if($end>$maxDrwNo) $end=$maxDrwNo;
for($i=$start;$i<=$end;$i++){
	if($i==$drwNo) echo "<a class=selected>$i</a>";
	else           echo '<a href='.$PHP_SELF.'?drwNo='.$i.'>'.$i.'</a>';
}

$nn = $drwNo+10;
$a_next = 'href='.$PHP_SELF.'?drwNo='.$nn;
if($nn>=$maxDrwNo) $a_next='href=javascript:;';
echo  "<a class=next $a_next>》</a>";

$n = $drwNo+1;
$next = 'href='.$PHP_SELF.'?drwNo='.$n;
if($n==$maxDrwNo) $next='href=javascript:;';
echo  "<a class='next' $next>다음</a>";
echo '</div>';
// 디비종료
$db->close();

?>
        <a href="history.php">History</a> | 
        <a href="index.html">Index</a> | 
        <a href="have_rank.php">보유번호 당첨수</a>
