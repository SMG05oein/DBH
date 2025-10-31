<?php
include '../../inc/loader.php';
//rr($_POST);
//if(isLogin()){
//    echo "<script>alert('잘못된 접근입니다.'); location.href='/DBH/src/Members/Login.php'</script>";
//}
$WhatIsForm = $_POST["WhatIsForm"];

$table = "members";
//echo json_encode($WhatIsForm);

//password_hash('문자열', PASSWORD_DEFAULT); /** 비번 DB에 암호화해서 넣을 거면 이 함수 쓰면 됨 */

if($WhatIsForm == "1"){ //Login.php
    $userId = $_POST["userId"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM dbh.members WHERE user_id = '$userId' AND user_pass = '$password'";
    $result = O($sql);
    $response = [];

    if($result === true){ // 유저 색인 후 정보가 없을 떄
        $response = ['isUser'=>'No'];
        echo json_encode($response);
        exit;
    }else{
        $response = ['isUser'=>'Yes'];
//        exit;
        $data = array(
            'last_login_date' => 'NOW()',
            'login_yn' => 1
        );

        $where = array(
            'user_id' => $userId,
        );

        UPDATE($table, $data, $where);

        $expire_time = time() + (60 * 30); // 30분간 유효
        setcookie("user_id", $userId, $expire_time, "/");
        echo json_encode($response);
    }

}else if($WhatIsForm == "2"){ //SignUp.php
    $userId = $_POST["userId"];
    $password = $_POST["password"];
    $nickname = $_POST["nickname"];
    $userName = $_POST["userName"];
    $birth = $_POST["birth"];
    $phone1 = $_POST["phone1"];
    $phone2 = $_POST["phone2"];
    $phone3 = $_POST["phone3"];
    $phone = $phone1.$phone2.$phone3;
    $data = array(
        "user_pass" => $password,
        "nickname" => $nickname,
        "user_id" => $userId,
        "user_name" => $userName,
        "birth" => $birth,
        'reg_date' => 'NOW()',
        "login_yn" => '0',
        'phone' => $phone,
    );

    $result = INSERT($table,$data);
    if($result){
        exit;
    }else{
        echo "<script>location.href='/DBH/src/Members/Login.php';alert('성공적으로 회원 가입 되었습니다.')</script>";
    }
}else if($WhatIsForm == "3"){ //유저 아이디 유효성 검사
    $userId = $_POST["userId"];
    $sql = "SELECT * FROM members WHERE user_id='$userId'";
    $row = O($sql);

    $response = ['empty' => $row === true ? 'empty' : 'notEmpty'];

    echo json_encode($response);
//    $response = ['empty' => count($row) == 1 ? "Empty" : "notEmpty"];
//    echo json_encode($row);
//    echo json_encode($response);
}else if($WhatIsForm == "4"){ //회원 탈퇴
    $userId = $_POST["user_id"];

    $where = array('user_id' => $userId);
    setcookie('user_id', "", time() - 3600, "/");
//    exit;
    DEL($table, $where,'');
    echo "<script>location.href='/DBH/src/index/index.php';</script>";

}



else if($WhatIsForm == "9999"){ //로그아웃
    $userId = $_POST["user_id"];
    $response = ['answer'=>'YES'];
//        exit;
    $data = array(
//        'last_login_date' => 'NOW()',
        'login_yn' => 0
    );

    $where = array(
        'user_id' => $userId,
    );

    UPDATE($table, $data, $where);
    echo json_encode($response);

}

?>