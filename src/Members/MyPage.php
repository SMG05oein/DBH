<?php
include("../../inc/head.php");
$user_id = isset($_COOKIE["user_id"])? $_COOKIE["user_id"]:0;

$sql = "SELECT * FROM dbh.members WHERE user_id='$user_id'";
$row = O($sql);

if($row === true){
    echo "<script>alert('로그인 정보가 없습니다.'); location.href='/DBH/src/index/index.php'</script>";
}else{
//    rr($row);
    $user_pass = $row["user_pass"];
    $user_name = $row["user_name"];
    $nickname = $row["nickname"];
    $birth = $row["birth"];
    $fk_department_code = $row["fk_department_code"];
    $reg_date = $row["reg_date"];

    $sql = "SELECT department_code,department_name FROM dbh.departments WHERE department_code='$fk_department_code'";
    $row = O($sql);
    $department_code = $row["department_code"];
    $department_name = $row["department_name"];
    $sql = "SELECT department_code,department_name FROM dbh.departments";
    $rows = A($sql);
}

?>

<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg">

                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">**마이페이지 - 내 정보**</h4>
                </div>

                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <form action="./MyPageUpdate.php" id="MypageForm" method="post">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">아이디 (ID)</span>
                                <input type="text" class="text-muted form-control w-25 text-end" value="<?=$user_id?>" disabled/>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">이름</span>
                                <input class="text-muted form-control w-25 text-end" type="text" value="<?=$user_name?>" disabled/>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">닉네임</span>
                                <input class="text-success form-control w-25 text-end" type="text" value="<?=$nickname?>" name="nickname" id="nickname"/>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">생년월일</span>
                                <input type="date" class="form-control w-25 text-end" value="<?=$birth?>" name="birth" id="birth"/>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">학부/전공</span>
                                <select class="form-select w-25 text-end" data-control="select2" name="department_code">
                                    <option value="">학부/전공</option>
                                    <?php foreach($rows as $row){?>
                                        <option value="<?=$row['department_code']?>" <?=$row['department_code'] == $department_code ? "selected" : ""?>><?=$row['department_name']?></option>
                                    <?php }?>
                                </select>
                            </li>
                        </form>

                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold text-dark">가입 일시</span>
                            <span class="text-secondary"><?=substr($reg_date,0, 10)?></span>
                        </li>
                    </ul>
                </div>

                <div class="card-footer d-grid gap-2 d-md-flex justify-content-md-end p-3">
                    <a class="btn btn-outline-primary" onclick="$('#MypageForm').submit()">정보 수정</a>
                    <a class="btn btn-danger" href="Login.php">로그아웃</a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php
include("../../inc/footer.php");
?>
