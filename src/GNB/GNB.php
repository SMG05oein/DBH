<?php
//include_once '../../inc/loader.php';
//print_r($_COOKIE);
$user_id = isset($_COOKIE["user_id"])? $_COOKIE["user_id"]:0;

$sql = "SELECT * FROM dbh.members WHERE user_id='$user_id'";
$row = O($sql);
?>

<section class="GNB d-flex gap-4">
    <div class="d-flex justify-content-center align-content-center">
        <div class="GNBTITLE d-flex align-items-center me-2" id="Title">DBH</div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a>dsad</a>
        <a>dsad</a>
        <a>dsad</a>
        <a>dsad</a>
    </div>

    <div class="d-flex justify-content-end align-items-center gap-2" style="width: 100%;">
        <a href="../Members/Login.php" class="LoginTools d-flex justify-content-center align-content-center">
            <?=$user_id !== 0 ? "로그아웃" : "로그인"?>
        </a>
        <?php if($user_id !== 0) {?>
            <a href="../Members/SignUp.php" class="LoginTools d-flex justify-content-center align-content-center ms-2">
                마이페이지
            </a>
        <?php }else{?>
            <a href="../Members/SignUp.php" class="LoginTools d-flex justify-content-center align-content-center ms-2">
                회원가입
            </a>
        <?php }?>
    </div>
</section>

<script>
    $('#Title').on('click', () => {
        location.href="/DBH/src/index/index.php"
    })
</script>
