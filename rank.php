<?php
header('Content-Type: text/json; charset=UTF-8');
// 회차번호
$drwNo = $_GET['drwNo'];
if(!$drwNo) $drwNo=1;

// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// 저장된 값이 있으면 
$sql = "SELECT drwNo FROM rnk WHERE drwNo=$drwNo LIMIT 0,1";
echo $sql.'<Br>';
$result = $db->query($sql) or die($_SERVER['PHP_SELF'].' line '.__LINE__.', SQL : '.$sql);
$row = $result->fetchArray(SQLITE3_ASSOC);
//print_r($row);
//exit;
if($row){ //처리 종료
	// 성공했음
	echo '{drwNo:'.$drwNo.',msg:"있음"}';
	// 디비종료
	$db->close();
	exit;
}


// 지정회차의 보유번호 당첨 등수
$sql="
insert into rnk(drwNo, have, rnk)
select e.drwNo as drwNo
     , c.no as haveNo
     , case d.cnt when 3 then 5
                  when 4 then 4
                  when 5 then case e.bnusNo when c.drwtNo1 then 2
                                            when c.drwtNo2 then 2
                                            when c.drwtNo3 then 2
                                            when c.drwtNo4 then 2
                                            when c.drwtNo5 then 2
                                            when c.drwtNo6 then 2
                              else 3 end
                  when 6 then 1 end as rank
  from have c
     , (
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
       ) d
     , (select drwNo, bnusNo from history where drwNo=$drwNo) e
 where c.no=d.no
";

$db->busyTimeout(1000);
$resultInsert = $db->exec($sql) or die($PHP_SELF.' line '.__LINE__.', SQL : '.$sql);
#$json_data = array();
#// 뽑혀진 데이타만큼 출력함
#while($row = $result->fetchArray(SQLITE3_ASSOC)){ $json_data[]=$row;
#echo json_encode($json_data);

// 성공했음
echo '{drwNo:'.$drwNo.',msg:"추가"}';

// 디비종료
$db->close();

?>