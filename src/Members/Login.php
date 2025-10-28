<?php
include("../../inc/head.php");
include"./loginCheck.php";

?>

<section class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4 custom-login-card">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">로그인</h2>
            <div style="color:red" class="mb-2" id="alertDiv"></div>
            <form action="#" id="loginForm" method="POST">
                <div class="mb-3">
                    <label for="userId" class="form-label">아이디</label>
                    <input type="text"
                           class="form-control"
                           id="userId"
                           name="userId"
                           placeholder="아이디를 입력하세요"
                           required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">비밀번호</label>
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="비밀번호를 입력하세요"
                           required>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-2">
                    <button type="button" id="loginBtn" class="btn btn-primary w-75 mb-3">로그인</button>
                    <a href="../index/index.php" class="btn btn-dark w-75 mb-3">홈으로</a>
                </div>

                <div class="text-center">
                    <a href="./SignUp.php" class="text-decoration-none me-3">회원가입</a>
                    <span class="text-muted">|</span>
                    <a href="#" class="text-decoration-none ms-3">비밀번호 찾기</a>
                </div>

            </form>
        </div>
    </div>
</section>

<script>
    const $loginForm = $('#loginForm')
    const loginBtn = $('#loginBtn')
    loginBtn.on('click', (e) => {
        e.preventDefault();
        $.ajax({
            url: "Auth_ok.php",
            method: "POST",
            data: {
                userId: $("#userId").val(),
                password: $("#password").val(),
                WhatIsForm: 1
            },
            dataType: "json",
            success: (r) => {
                console.log(r);
                if(r.isUser == 'No'){
                    $('#alertDiv').text("아이디 혹은 비밀번호가 맞지 않습니다.")
                }else{
                    location.href = "../index/index.php"
                }
            },
            error: (xhr, status, error) => {
                console.log("AJAX 요청 실패!");
                console.log("상태(status):", status);
                console.log("오류(error):", error);
                console.log("응답 텍스트:", xhr.responseText);
                $('#alertDiv').text("서버와의 통신에 문제가 발생했습니다. " + error);
            }
        })
    })
</script>

<?php
include("../../inc/footer.php");
?>