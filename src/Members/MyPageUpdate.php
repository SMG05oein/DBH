<?php
include "../../inc/loader.php";
$user_id = isset($_COOKIE["user_id"])? $_COOKIE["user_id"]:0;

if(isLogin()){
    echo "<script>alert('잘못된 접근입니다.'); location.href='/DBH/src/index/index.php'</script>";

}
//rr($_POST);
$nickname = $_POST["nickname"];
$birth = $_POST["birth"] !='' ? $_POST["birth"] : '1970-01-01';
$department_code = $_POST["department_code"];
$phone1 = $_POST["phone1"];
$phone2 = $_POST["phone2"];
$phone3 = $_POST["phone3"];
$phone = $phone1.$phone2.$phone3;
$table = "dbh.members";
$data = array(
    "nickname" => $nickname,
    "birth" => $birth,
    "fk_department_code" => $department_code,
    'phone' => $phone,
);
$where = array('user_id' => $user_id);

UPDATE($table, $data, $where);
echo "<script>history.back()</script>"
?>

