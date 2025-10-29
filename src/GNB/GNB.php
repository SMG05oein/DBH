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
        <a href="../Members/Login.php" id="login" class="LoginTools d-flex justify-content-center align-content-center">
            <?=$user_id !== 0 ? "로그아웃" : "로그인"?>
        </a>
        <?php if($user_id !== 0) {?>
            <a href="../Members/MyPage.php" class="LoginTools d-flex justify-content-center align-content-center ms-2">
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

    $('#login').on('click', (e) => {
        const l = $('#login').text().trim();
        console.log(l);
        if(l == '로그아웃'){
            e.preventDefault();
            if(confirm('로그아웃 하시겠습니까?')) {
                function deleteCookie(name, path) {
                    const expiredDate = 'Thu, 01 Jan 1970 00:00:00 UTC';

                    // 2. document.cookie에 삭제할 쿠키 정보를 덮어씁니다.
                    // 주의: name과 path는 설정 시와 반드시 일치해야 합니다.
                    document.cookie = name + '=; expires=' + expiredDate + '; path=' + path;

                    /** 가능하할 때 ajax통해서 login_yn을 0으로 바꾸는 작업까지*/
                }
                deleteCookie('user_id', '/');
                location.href="/DBH/src/index/index.php";
            }
        }else{

        }
    })
</script>
