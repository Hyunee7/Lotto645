<?php
//header( 'Content-type: text/json' );
header('Content-Type: text/json; charset=UTF-8');
@error_reporting(E_ALL ^ E_NOTICE);

// 회차번호
$drwNo = $_GET['drwNo'];

// 디비파일 연결
$db = new SQLite3('lotto.sqlite3', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

/*
// 테이블생성
$sql = "
CREATE TABLE IF NOT EXISTS history (
	drwNo            INTEGER PRIMARY KEY AUTOINCREMENT,
	returnValue      VARCHAR(7),
	drwNoDate        VARCHAR(10),
	totSellamnt      FLOAT,
	firstWinamnt     FLOAT,
	firstPrzwnerCo   INT,
	firstAccumamnt   INT,
	drwtNo1          INT,
	drwtNo2          INT,
	drwtNo3          INT,
	drwtNo4          INT,
	drwtNo5          INT,
	drwtNo6          INT,
	bnusNo           INT,
	result           TEXT, 
	reg_date         DATETIME DEFAULT (datetime('now','localtime')),
	mod_date         DATETIME DEFAULT (datetime('now','localtime'))
);
";
$db->exec($sql) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);
echo 'table ok';
*/
// 데이타 조회
$sql = "select * from history where drwNo=$drwNo";
$data = $db->querySingle($sql, true);// or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);
if($data){                                 // 저장된 데이타가 있으면
	if($data['returnValue'] == 'success'){ // 수신상태가 성공이면  출력하고 종료함.
		// 히스토리 저장
		$ip    = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$http_referer = addslashes($_SERVER['HTTP_REFERER']);
		$sql = "insert into history_log(drwNo,ip,agent,http_referer) "
			  ."values('$drwNo','$ip','$agent','$http_referer')";
		$tf = $db->busyTimeout(10000);
		$db->exec($sql) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);

		// 디비종료
		if($tf) $db->close();
		echo $data['result'];
		exit;
	}
}

//$url = 'http://www.645lotto.net/common.do?method=getLottoNumber&drwNo='.$_GET['drwNo'];
//$url = 'https://www.645lotto.net/common.do?method=getLottoNumber&drwNo='.$_GET['drwNo'];
//$url = 'https://www.nlotto.co.kr/common.do?method=getLottoNumber&drwNo='.$drwNo;
//$url = 'https://www.dhlottery.co.kr/common.do?method=getLottoNumber&drwNo='.$_GET['drwNo'];
$url = 'https://www.dhlottery.co.kr/lt645/selectPstLt645Info.do?srchLtEpsd='.$_GET['drwNo'];
//$response = file_get_contents($url);
/**/
// 사업자 변경됨에따라 수정함.
// 2019-01-10
//$ua = "'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116'";
$ua = "'Mozilla/5.0 (Windows NT 6.1; WOW64; ; NCLIENT50_AAPC82B145F1B2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36'";
$timeout = 5;
//$timeout = 10;
//$req_timeout = 10;
$req_timeout = 42;
@mb_internal_encoding("UTF-8");
@date_default_timezone_set('Asia/Seoul');
error_reporting(E_ALL ^ E_NOTICE); //주석풀면 에러 안나옴
@set_time_limit(0);
define("VERSION", "0.0.2");
//$url = 'https://www.nlotto.co.kr/common.do?method=getLottoNumber&drwNo='.$_GET['drwNo'];
//echo $url.'\n';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL           , $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_TIMEOUT       , $req_timeout);
curl_setopt($ch, CURLOPT_HEADER        , False);
curl_setopt($ch, CURLOPT_FAILONERROR   , True);
curl_setopt($ch, CURLOPT_USERAGENT     , $ua);
$response = curl_exec($ch);
//if(curl_error($ch) && $GLOBALS['debug']) printError($url." ".curl_error($ch));
if(curl_error($ch)) echo curl_error($ch);
curl_close($ch);

// 수신내용 디코딩
$lotto = json_decode($response);
if(isset($lotto->returnValue)){
//	echo '0-0-0-0-0-0-0-0--0-';
	$returnValue    = $lotto->returnValue   ;
	$drwNoDate      = $lotto->drwNoDate     ;
	$totSellamnt    = $lotto->totSellamnt   ;
	$firstWinamnt   = $lotto->firstWinamnt  ;
	$firstPrzwnerCo = $lotto->firstPrzwnerCo;
	$firstAccumamnt = $lotto->firstAccumamnt;
	$drwtNo1        = $lotto->drwtNo1       ;
	$drwtNo2        = $lotto->drwtNo2       ;
	$drwtNo3        = $lotto->drwtNo3       ;
	$drwtNo4        = $lotto->drwtNo4       ;
	$drwtNo5        = $lotto->drwtNo5       ;
	$drwtNo6        = $lotto->drwtNo6       ;
	$bnusNo         = $lotto->bnusNo        ;
}else{
//	echo '1-1-1-1-1-1-1-1--1-';
//	$returnValue    = $lotto->resultCode   ;
	$returnValue    = 'success'   ;
	$drwNoDate      = $lotto->data->list[0]->ltRflYmd     ;
	$totSellamnt    = $lotto->data->list[0]->rlvtEpsdSumNtslAmt   ;
	$firstWinamnt   = $lotto->data->list[0]->rnk1WnAmt  ;
	$firstPrzwnerCo = $lotto->data->list[0]->rnk1WnNope;
	$firstAccumamnt = 0;
	$drwtNo1        = $lotto->data->list[0]->tm1WnNo       ;
	$drwtNo2        = $lotto->data->list[0]->tm2WnNo       ;
	$drwtNo3        = $lotto->data->list[0]->tm3WnNo       ;
	$drwtNo4        = $lotto->data->list[0]->tm4WnNo       ;
	$drwtNo5        = $lotto->data->list[0]->tm5WnNo       ;
	$drwtNo6        = $lotto->data->list[0]->tm6WnNo       ;
	$bnusNo         = $lotto->data->list[0]->bnsWnNo        ;
	//echo '$bnusNo['.$bnusNo.']';
}

//echo '$returnValue['.$returnValue.']<Br>';
if($returnValue=='success'){ // 실패시 : {"returnValue":"fail"}
//	echo "===============================<Br>";
	//	drwNo            INTEGER PRIMARY KEY AUTOINCREMENT,
	$result         = $response;


	if($data){ // 오류데이타가 있는경우
		$sql = "update history set returnValue='$returnValue', drwNoDate='$drwNoDate', totSellamnt=$totSellamnt, firstWinamnt=$firstWinamnt,
								   firstPrzwnerCo=$firstPrzwnerCo, firstAccumamnt=$firstAccumamnt,
								   drwtNo1=$drwtNo1, drwtNo2=$drwtNo2, drwtNo3=$drwtNo3, drwtNo4=$drwtNo4, drwtNo5=$drwtNo5, drwtNo6=$drwtNo6,
								   bnusNo=$bnusNo, result='$result', mod_date=DATETIME('now','localtime') 
						where drwNo=$drwNo";
	} else { // 데이타가 없는경우
		$sql = "insert into history(drwNo, returnValue, drwNoDate, totSellamnt, firstWinamnt,
									firstPrzwnerCo, firstAccumamnt,
									drwtNo1, drwtNo2, drwtNo3, drwtNo4, drwtNo5, drwtNo6,
									bnusNo, result) "
			 .              "values($drwNo,'$returnValue', '$drwNoDate', $totSellamnt, $firstWinamnt,
									$firstPrzwnerCo, $firstAccumamnt,
									$drwtNo1, $drwtNo2, $drwtNo3, $drwtNo4, $drwtNo5, $drwtNo6,
									$bnusNo, '$result')";
	}
//	echo $sql;
	$db->busyTimeout(1000);
	$db->exec($sql) or die($_SERVER["PHP_SELF"].' line '.__LINE__.', SQL : '.$sql);
}

// 디비종료
$db->close();

//$response = getRemoteFile($url);
echo $response;


/**/
function getRemoteFile($url)
{
   // host name 과 url path 값을 획득
   $parsedUrl = parse_url($url);
   $host = $parsedUrl['host'];
   if (isset($parsedUrl['path'])) {
      $path = $parsedUrl['path'];
   } else {
      // url이 http://www.mysite.com 같은 형식이라면
      $path = '/';
   }
 
   if (isset($parsedUrl['query'])) {
      $path .= '?' . $parsedUrl['query'];
   } 
 
   if (isset($parsedUrl['port'])) {
      $port = $parsedUrl['port'];
   } else {
      // 대부분의 사이트들은 80포트를 사용
      $port = '80';
   }
 
   $timeout = 10;
   $response = '';
   // 원격 서버에 접속한다
   $fp = @fsockopen($host, $port, $errno, $errstr, $timeout );
 
   if( !$fp ) {
      echo "Cannot retrieve $url";
   } else {
      // 필요한 헤더들 전송
      fputs($fp, "GET $path HTTP/1.0\r\n" .
                 "Host: $host\r\n" .
                 "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
                 "Accept: */*\r\n" .
                 "Accept-Language: en-us,en;q=0.5\r\n" .
                 "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                 "Keep-Alive: 300\r\n" .
                 "Connection: keep-alive\r\n" .
                 "Referer: http://$host\r\n\r\n");
 
      // 원격 서버로부터 response 받음
      while ( $line = fread( $fp, 4096 ) ) {
         $response .= $line;
      }
 
      fclose( $fp );
 
      // header 부분 걷어냄
      $pos      = strpos($response, "\r\n\r\n");
      $response = substr($response, $pos + 4);
   }
 
   // 파일의 content 리턴
   return $response;
}
?>
