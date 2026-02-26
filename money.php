<?php
header( 'Content-type: text/json' );
$drwNo = $_GET['drwNo'];

// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// 저장된 값이 있으면 
$sql = "SELECT dhl FROM history WHERE drwNo=$drwNo LIMIT 0,1";
//echo $sql.'<Br>';
$result = $db->query($sql) or die($_SERVER['PHP_SELF'].' line '.__LINE__.', SQL : '.$sql);
$row = $result->fetchArray(SQLITE3_ASSOC);
//print_r($row);
//exit;
if($row){
	if($row['dhl']!=''){ //데이타가 있는경우 종료
		// 성공했음
		//echo '{drwNo:'.$drwNo.',msg:"있음"}';
		//echo '$row[\'dhl\']'.$row['dhl'];
		echo $row['dhl'];
		// 디비종료
		$db->close();
		exit;
	}
}


//https://www.nlotto.co.kr/common.do?method=byWin&drwNo=882 //test

//$url = 'https://www.nlotto.co.kr/gameResult.do?method=byWin&drwNo='.$_GET['drwNo'];
//$url = 'https://www.645lotto.co.kr/gameResult.do?method=byWin&drwNo='.$_GET['drwNo'];
//$url = 'https://dhlottery.co.kr/gameResult.do?method=byWin&drwNo='.$_GET['drwNo'];
//$url = 'https://www.dhlottery.co.kr/lt645/selectPstLt645InfoNew.do?srchDir=older&srchCursorLtEpsd='.$_GET['drwNo'];
$url = 'https://www.dhlottery.co.kr/lt645/selectPstLt645InfoNew.do?srchDir=older&srchCursorLtEpsd='.($drwNo+1);
//echo '$url:'.$url;
$ua = "'Mozilla/5.0 (Windows NT 6.1; WOW64; ; NCLIENT50_AAPC82B145F1B2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36'";
$timeout = 5;
$req_timeout = 42;
@mb_internal_encoding("UTF-8");
@date_default_timezone_set('Asia/Seoul');
error_reporting(E_ALL ^ E_NOTICE); //주석풀면 에러 안나옴
@set_time_limit(0);
define("VERSION", "0.0.2");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_TIMEOUT, $req_timeout);
curl_setopt($ch, CURLOPT_HEADER, False);
curl_setopt($ch, CURLOPT_FAILONERROR, True);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
$response = curl_exec($ch);
if(curl_error($ch)) echo curl_error($ch);
curl_close($ch);

if($row){ // 수신된 정보 업데이트
		$sql = "update history set dhl='$response' where drwNo=$drwNo";
	$db->busyTimeout(1000);
	$db->exec($sql) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);
}

echo $response;



$db->close();

?>