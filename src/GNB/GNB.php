<?php
?>

<section class="GNB d-flex gap-4">
    <div>
        <div class="GNBTITLE d-flex" id="Title">DBH</div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a>dsad</a>
        <a>dsad</a>
        <a>dsad</a>
        <a>dsad</a>
    </div>

    <div class="d-flex justify-content-end align-items-center gap-2" style="width: 100%;">
        <a href="../Members/Login.php" class="LoginTools d-flex justify-content-center align-content-center">
            로그인
        </a>
        <a href="../Members/SignUp.php" class="LoginTools d-flex justify-content-center align-content-center">
            회원가입
        </a>
    </div>
</section>

<script>
    $('#Title').on('click', () => {
        location.href="/DBH/src/index/index.php"
    })
</script>
