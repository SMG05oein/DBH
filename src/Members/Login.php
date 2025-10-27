<?php
include("../../inc/head.php");
include "../../inc/loader.php";
?>

<section class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4 custom-login-card">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">로그인</h2>

            <form action="#" method="POST">
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
                    <button type="submit" class="btn btn-primary w-75 mb-3">로그인</button>
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

<?php
include("../../inc/footer.php");
?>