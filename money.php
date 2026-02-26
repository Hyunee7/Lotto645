<?php
// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);


header( 'Content-type: text/json' );
//https://www.nlotto.co.kr/common.do?method=byWin&drwNo=882 //test

//$url = 'https://www.nlotto.co.kr/gameResult.do?method=byWin&drwNo='.$_GET['drwNo'];
//$url = 'https://www.645lotto.co.kr/gameResult.do?method=byWin&drwNo='.$_GET['drwNo'];
$url = 'https://dhlottery.co.kr/gameResult.do?method=byWin&drwNo='.$_GET['drwNo'];
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


echo $response;

$db->close();

?>