/*
object(stdClass)#2 (14) {
  ["totSellamnt"]=>  float(56561977000)
  ["returnValue"]=>  string(7) "success"
  ["drwNoDate"]=>  string(10) "2004-10-30"
  ["firstWinamnt"]=>  float(3315315525)
  ["drwtNo6"]=>  int(42)
  ["drwtNo4"]=>  int(23)
  ["firstPrzwnerCo"]=>  int(4)
  ["drwtNo5"]=>  int(37)
  ["bnusNo"]=>  int(6)
  ["firstAccumamnt"]=>  int(0)
  ["drwNo"]=>  int(100)
  ["drwtNo2"]=>  int(7)
  ["drwtNo3"]=>  int(11)
  ["drwtNo1"]=>  int(1)
}
<hr>{"totSellamnt":56561977000,"returnValue":"success","drwNoDate":"2004-10-30","firstWinamnt":3315315525,"drwtNo6":42,"drwtNo4":23,"firstPrzwnerCo":4,"drwtNo5":37,"bnusNo":6,"firstAccumamnt":0,"drwNo":100,"drwtNo2":7,"drwtNo3":11,"drwtNo1":1}
*/
-- 당첨번호 내역
CREATE TABLE IF NOT EXISTS history (  -- 당첨번호 내역
	drwNo            INTEGER PRIMARY KEY AUTOINCREMENT,  -- 회차번호
	returnValue      VARCHAR(7),                         -- 정상여부 success|fail
	drwNoDate        VARCHAR(10),                        -- 추첨일
	totSellamnt      FLOAT,                              -- 총판매금액
	firstWinamnt     FLOAT,                              -- 1등 당첨금
	firstPrzwnerCo   INT,                                -- 1등 당첨자수
	firstAccumamnt   INT,                                -- 1등 당첨금 총액
	drwtNo1          INT,                                -- 첫번째 번호
	drwtNo2          INT,                                -- 두번째 번호
	drwtNo3          INT,                                -- 세번째 번호
	drwtNo4          INT,                                -- 네번째 번호
	drwtNo5          INT,                                -- 다섯번째 번호
	drwtNo6          INT,                                -- 여섯번째 번호
	bnusNo           INT,                                -- 보너스 번호
	result           TEXT,                               -- 수신문자열(JSON)
	reg_date         DATETIME DEFAULT (DATETIME('now','localtime')), -- 등록일시
	mod_date         DATETIME DEFAULT (DATETIME('now','localtime'))  -- 수정일시
);
--CREATE INDEX idx_history ON history (drwNo);

-- 당첨번호 읽은내역
CREATE TABLE IF NOT EXISTS history_log ( -- 당첨번호 읽은내역
	no            INTEGER PRIMARY KEY AUTOINCREMENT,              -- 번호
	drwNo         INTEGER NOT NULL,                               -- 회차번호
	IP            char( 16) DEFAULT NULL,                         -- IP
	AGENT         char(255) DEFAULT NULL,                         -- 브라우저
    HTTP_REFERER  char(255) DEFAULT NULL,                         -- 이전경로
	reg_date      DATETIME DEFAULT (DATETIME ('now','localtime')) -- 등록일시
);
CREATE INDEX idx_history_log ON history_log (drwNo,IP);


-- 히스토리별 보유번호 당점 등수
CREATE TABLE IF NOT EXISTS rnk ( -- 히스토리별 보유번호 당점 등수
	no            INTEGER PRIMARY KEY AUTOINCREMENT,              -- 번호
	drwNo         INTEGER NOT NULL,                               -- 회차번호
    have          INTEGER NOT NULL,                               -- 보유번호
    rnk           INTEGER default 0,                                  -- 보유번호
	reg_date      DATETIME DEFAULT (DATETIME ('now','localtime')) -- 등록일시
);
CREATE INDEX idx_rnk ON rnk (drwNo,have);


-- 보유 번호 내역
CREATE TABLE IF NOT EXISTS have ( -- 보유 번호 내역
	no               INTEGER PRIMARY KEY AUTOINCREMENT,              -- 번호
	use              INT DEFAULT 1,                                  -- 사용여부 1:사용, 0:폐기
	paper            INT DEFAULT 0,                                  -- 종이구분(한페이지)
	auto             INT DEFAULT 0,                                  -- 자동여부 1:자동, 0:수동
	drwtNo1          INT,                                            -- 첫번째 번호
	drwtNo2          INT,                                            -- 두번째 번호
	drwtNo3          INT,                                            -- 세번째 번호
	drwtNo4          INT,                                            -- 네번째 번호
	drwtNo5          INT,                                            -- 다섯번째 번호
	drwtNo6          INT,                                            -- 여섯번째 번호
	reg_date         DATETIME DEFAULT (DATETIME('now','localtime'))  -- 등록일시
);
CREATE INDEX idx_have1 ON have (use);
CREATE INDEX idx_have2 ON have (use,paper);
CREATE INDEX idx_have3 ON have (use,auto);
CREATE INDEX idx_have4 ON have (use,paper,auto);

-- 2021-10-10(일) 보유 번호들
INSERT INTO have  (paper, drwtNo1, drwtNo2, drwtNo3, drwtNo4, drwtNo5, drwtNo6)
       VALUES( 1,  1, 18, 19, 34, 36, 38),
             ( 1,  1,  8, 14, 27, 28, 38),
             ( 1,  6, 15, 24, 29, 31, 41),
             ( 1,  7, 12, 29, 30, 33, 35),
             ( 1,  6, 25, 27, 34, 41, 43),

             ( 6,  2, 18, 20, 24, 30, 39),
             ( 6,  4, 12, 13, 16, 17, 38),
             ( 6,  2, 16, 23, 29, 34, 40),
             ( 6,  1, 13, 22, 28, 38, 40),
             ( 6, 14, 19, 22, 35, 40, 41),

             (11,  8, 11, 12, 37, 38, 45),
             (11,  3, 13, 17, 20, 26, 32),
             (11, 16, 18, 39, 43, 44, 45),
             (11,  5, 19, 21, 25, 28, 42),
             (11,  4, 10, 15, 20, 31, 43),

             (16,  1,  5, 12, 17, 34, 44),
             (16,  2,  7, 10, 18, 25, 32),
             (16,  8, 14, 19, 22, 30, 38),
             (16,  3,  6, 13, 25, 36, 37),
             (16,  1,  9, 31, 35, 36, 41),

             (21,  1,  6, 14, 31, 32, 38),
             (21,  2, 24, 26, 33, 41, 45),
             (21,  3, 21, 23, 26, 30, 42),
             (21, 10, 15, 20, 24, 31, 35),
             (21,  1,  7,  9, 14, 34, 45),

             (26,  3, 24, 25, 28, 32, 39),
             (26,  9, 21, 29, 30, 36, 38),
             (26, 18, 19, 26, 32, 38, 42),
             (26,  1,  8, 13, 16, 40, 45),
             (26,  5,  8, 28, 33, 34, 35),

             (31,  3, 15, 34, 35, 43, 45),
             (31,  2,  7, 13, 28, 30, 41),
             (31, 16, 17, 19, 20, 32, 36),
             (31,  2,  3, 17, 26, 30, 40),
             (31,  6,  8, 11, 13, 28, 39),

             (36,  4,  6,  9, 10, 16, 28),
             (36,  5,  7, 15, 19, 23, 32),
             (36,  4, 11, 17, 24, 34, 41),
             (36,  4,  6,  8, 23, 35, 41),
             (36,  6, 10, 23, 24, 30, 44),

             (41,  6,  7, 20, 22, 32, 33),
             (41,  5,  7, 11, 29, 31, 40),
             (41,  5,  7,  9, 10, 28, 44),
             (41,  5,  8, 11, 23, 33, 38),
             (41, 11, 15, 16, 29, 37, 43),

             (46,  4,  7, 27, 30, 33, 42),
             (46,  2, 14, 17, 27, 28, 29),
             (46,  3,  4, 10, 11, 21, 22),
             (46,  1,  9, 11, 14, 25, 34),
             (46,  2, 11, 16, 23, 29, 45),

             (51,  5,  7,  8, 13, 16, 33),
             (51,  1,  9, 10, 15, 17, 37),
             (51,  6,  7, 18, 28, 29, 44),
             (51,  6, 20, 29, 36, 40, 41),
             (51,  1, 13, 14, 29, 39, 44),

             (56,  5,  7, 10, 17, 19, 42),
             (56,  2, 27, 30, 33, 38, 43),
             (56,  3,  7, 15, 16, 37, 43),
             (56, 11, 23, 25, 26, 31, 41),
             (56,  5, 10, 30, 39, 41, 45);

/**************************************************************************************************

-- 당첨 보유번호 추출(2등은 추출하지 못함)
-- no 보유번호, cnt 맞은 번호 갯수
select no, cnt
  from (
        select a.no, count(a.no) as cnt
          from have a
             , (select drwtNo1 as drwtNo from history where drwNo=882
                union all
                select drwtNo2 as drwtNo from history where drwNo=882
                union all
                select drwtNo3 as drwtNo from history where drwNo=882
                union all
                select drwtNo4 as drwtNo from history where drwNo=882
                union all
                select drwtNo5 as drwtNo from history where drwNo=882
                union all
                select drwtNo6 as drwtNo from history where drwNo=882) b
         where a.use=1
        --   and a.paper=1
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


-- 당첨 보유번호 추출
select c.no
     , c.paper
     , c.drwtNo1
     , c.drwtNo2
     , c.drwtNo3
     , c.drwtNo4
     , c.drwtNo5
     , c.drwtNo6
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
     , case d.cnt when 5 then e.bnusNo
       else '' end as bnusNo
  from have c
     , (
        select no, cnt
          from (
                select a.no, count(a.no) as cnt
                  from have a
                     , (select drwtNo1 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo2 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo3 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo4 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo5 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo6 as drwtNo from history where drwNo=882) b
                 where a.use=1
                --   and a.paper=1
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
     , (select bnusNo from history where drwNo=882) e
 where c.no=d.no



-- 보유번호 목록의 당첨여부 확인
select c.no
     , c.paper
     , c.drwtNo1
     , c.drwtNo2
     , c.drwtNo3
     , c.drwtNo4
     , c.drwtNo5
     , c.drwtNo6
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
     , case d.cnt when 5 then e.bnusNo
       else '' end as bnusNo
  from have c left outer join
       (
        select no, cnt
          from (
                select a.no, count(a.no) as cnt
                  from have a
                     , (select drwtNo1 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo2 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo3 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo4 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo5 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo6 as drwtNo from history where drwNo=882) b
                 where a.use=1
                --   and a.paper=1
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
     , (select bnusNo from history where drwNo=882) e


-- 보유번호 목록의 당첨여부 확인(각 번호 당첨 여부 추가 1:당첨 2:보너스번호당첨)
select c.no
     , c.paper
     , c.drwtNo1
     , case c.drwtNo1 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo1
     , c.drwtNo2
     , case c.drwtNo2 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo2
     , c.drwtNo3
     , case c.drwtNo3 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo3
     , c.drwtNo4
     , case c.drwtNo4 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo4
     , c.drwtNo5
     , case c.drwtNo5 when e.drwtNo1 then 1
                      when e.drwtNo2 then 1
                      when e.drwtNo3 then 1
                      when e.drwtNo4 then 1
                      when e.drwtNo5 then 1
                      when e.drwtNo6 then 1
                      when e.bnusNo and d.cnt=5 then 2
       else '' end isDrwtNo5
     , c.drwtNo6
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
                     , (select drwtNo1 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo2 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo3 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo4 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo5 as drwtNo from history where drwNo=882
                        union all
                        select drwtNo6 as drwtNo from history where drwNo=882) b
                 where a.use=1
                --   and a.paper=1
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
     , (select drwtNo1, drwtNo2, drwtNo3, drwtNo4, drwtNo5, drwtNo6, bnusNo from history where drwNo=882) e

**************************************************************************************************/