
<html>
 <head>
  <title>로또확률계산기</title>
  <meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0,user-scalable=yes" />
  <script>
    var ball = [];
    var paper = [];
    var _history = [];
    var iMax = 1000;
    var iCnt = 3;

    for(var i=1; i<=45; i++) ball.push(i);

    function log(msg){
        debugDiv.innerHTML += msg + '<Br>';
    }
    function getRnd(max){
        return Math.floor(Math.random()*max);
    }
    function get6Ball(){
        var r=[]
        var ball_copy = [];
        ball.forEach(function(v){ball_copy.push(v)});
//        log(ball_copy);
        for(var i=0; i<45; i++){
            var rnd = getRnd(45);
            var tmp = ball_copy[i];
            ball_copy[i] = ball_copy[rnd];
            ball_copy[rnd] = tmp;
        }
//        log(ball_copy);
        for(var i=0; i<6; i++) {
            var rnd = getRnd(ball_copy.length);
//            log(rnd + ' : '+ball_copy[rnd]);
            r.push( ball_copy[rnd] );
            ball_copy.splice(rnd,1);
//            log(ball_copy)
        }
        return r.sort(function(a,b){return a<b?-1:a>b?1:0;});
    }
    function start(){
        startBtn.disabled = true;
        debugDiv.innerHTML = '';
        iMax = calc.value;
        iCnt = cntc.value;
	_history = [];
        log("추첨대상 : " + paper);
        for(var i=0; i<iMax; i++){
            var cmp = get6Ball();
            var cnt = 0;
            var kStart = 0;
            for(var j=0; j<cmp.length; j++){
                for(var k=kStart; k<paper.length; k++){
                    if(cmp[j]==paper[k]){
                        cnt++;
                        kStart++;
                        cmp[j]='('+cmp[j]+')';
                    }else if(cmp[j]>paper[k]){
                        kStart++;
                        continue;
                    }
                }
            }
            cmp.push(' : '+cnt);
            if(cnt>iCnt) _history.push([i+'회:'].concat(cmp));
        }
        log(_history.join('<Br>'));
        startBtn.disabled = false;
    }
    window.onload = function(){
        paper = get6Ball();

    }
   </script>
 </head>
 <body>
  계산회차 : <input type=text name=calc id=calc value=10000></input><Br/>
  <input type=text name=cntc id=cntc value=4></input>개 이상 맞으면 출력<Br/>
  <button id="startBtn" onclick="start()">시작</button>
  <div id='debugDiv'></div>
 </body>
</html>


