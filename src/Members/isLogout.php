<?php
$temp = isset($_COOKIE["user_id"])?$_COOKIE["user_id"]:0;
$sql = "SELECT * FROM dbh.members WHERE user_id = '$temp'";
if($temp !== 0){
    echo
    "<script>
    if(confirm('로그아웃 하시겠습니까?')){
        location.href='/DBH/src/index/index.php'
        function deleteCookie(name, path) {
        const expiredDate = 'Thu, 01 Jan 1970 00:00:00 UTC';
    
        // 2. document.cookie에 삭제할 쿠키 정보를 덮어씁니다.
        // 주의: name과 path는 설정 시와 반드시 일치해야 합니다.
        document.cookie = name + '=; expires=' + expiredDate + '; path=' + path;
    }
        deleteCookie('user_id', '/');
    }else{
        history.back()
    }
    </script>";
}
//$table = "dbh.members";
//$data = array('login_yn' => 0);
//$where = array('user_id' => $temp);
//UPDATE($table, $data, $where);
?>