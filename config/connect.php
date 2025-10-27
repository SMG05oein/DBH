<?php

/*
 * 이 파일은 저를 제외하고 깃허브에 올리지 마세요. 로컬 서버 연결이라 보안상 문제는 거의 없지만 원래는 DB 설정 파일입니다.
 * 아래 생성자에 들어갈 것 순서대로("localhost or 127.0.0.1","DB접속 아이디(대게 root)","DB접속 비번","DB이름")
 * 생성자 첫 번째 인자->원래는 접속 아이피인데 우린 로컬 서버에서 DB돌려서 localhost or 127.0.0.1
 * 생성자 두 번째 인자->수업에서 MySQL 워크벤처 로컬 서버에 들어갈 때 사용한 아이디
 * 생성자 세 번째 인자->수업에서 MySQL 워크벤처 로컬 서버에 들어갈 때 사용한 비번
 * 생성자 네 번째 인자->제가 독스에 올린 쿼리문 그대로 사용했다면 dbh
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

echo
"<script>
console.log('DB 연결 성공');
</script>";

?>

