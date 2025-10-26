<?php

/*
 * 이 파일은 첫 커밋 후(서민관 커밋 후) 깃허브에 올리지 마세요. 로컬호스트 연결이라 상관은 없지만 원래는 설정 파일입니다.
 * 아래 생성자에 들어갈 것 순서대로("localhost or 127.0.0.1","DB접속 아이디(대게 root)","DB접속 비번","DB이름")
 * 생성자 첫 번째 인자->(원래는 접속 아이피인데 우린 로컬 서버에서 DB돌려서)
 * */

$DBCONF = array();
$DBCONF['host'] = "";
$DBCONF['user'] = "";
$DBCONF['pass'] = "";
$DBCONF['dbname'] = "";

$mysqli = new mysqli($DBCONF['host'], $DBCONF['user'],$DBCONF['pass'],$DBCONF['dbname']);

if ($mysqli->connect_error) {
    die("연결 실패: " . $mysqli->connect_error);
}

echo "MySQLi 연결 성공!";

?>

