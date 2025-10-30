<?php
include "../../inc/loader.php";
$user_id = isset($_COOKIE["user_id"])? $_COOKIE["user_id"]:0;

//rr($_POST);
$nickname = $_POST["nickname"];
$birth = $_POST["birth"]=='' ? $_POST["birth"] : "null";
$department_code = $_POST["department_code"];
$table = "dbh.members";
$data = array(
    "nickname" => $nickname,
    "birth" => $birth,
    "fk_department_code" => $department_code
);
$where = array('user_id' => $user_id);

UPDATE($table, $data, $where);
echo "<script>history.back()</script>"
?>

