<?php
include("../../inc/head.php");
?>

<section class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4 custom-register-card">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">회원가입</h2>

            <form action="Auth_ok.php" id="SignUpForm" method="POST">
                <input type="hidden" name="WhatIsForm" value="2">
                <div class="mb-2" id="alert" style="color: red"></div>
                <div class="mb-3">
                    <label for="userId" class="form-label">아이디<em style="color: red">*</em>
                        <button type="button" id="checkId" class="btn btn-dark" style="font-size:0.7rem">아이디 중복 확인</button>
                    </label>
                    <input type="text"
                           class="form-control"
                           id="userId"
                           name="userId"
                           placeholder="학번을 입력하세요"
                           required>

                </div>

                <label  for="passwordOk" class="form-label">전화번호<em style="color: red">*</em></label>
                <div class="mb-3 d-flex justify-content-center justify-content-center align-items-center gap-2">
                    <input type="text"
                           class="form-control" style="max-width: 100px;"
                           id="phone1"
                           name="phone1"
                           placeholder="전화번호를 입력하세요"
                           required>
                    -
                    <input type="text"
                           class="form-control" style="max-width: 100px;"
                           id="phone2"
                           name="phone2"
                           placeholder="전화번호를 입력하세요"
                           required>
                    -
                    <input type="text"
                           class="form-control" style="max-width: 100px;"
                           id="phone3"
                           name="phone3"
                           placeholder="전화번호를 입력하세요"
                           required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">비밀번호<em style="color: red">*</em></label>
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="비밀번호를 입력하세요"
                           required>
                </div>
                <div class="mb-3">
                    <label for="passwordOk" class="form-label">비밀번호 확인<em style="color: red">*</em></label>
                    <input type="password"
                           class="form-control"
                           id="passwordOk"
                           name="passwordOk"
                           placeholder="비밀번호를 다시 입력하세요"
                           required>
                </div>
                <div class="mb-3">
                    <label for="passwordOk" class="form-label">이름<em style="color: red">*</em></label>
                    <input type="text"
                           class="form-control"
                           id="userName"
                           name="userName"
                           placeholder="이름을 입력하세요"
                           required>
                </div>
                <div class="mb-3">
                    <label for="passwordOk" class="form-label">생일</label>
                    <input type="date"
                           class="form-control"
                           id="birth"
                           name="birth"
                           >
                </div>
                <div class="mb-3">
                    <label for="passwordOk" class="form-label">닉네임</label>
                    <input type="text"
                           class="form-control"
                           id="nickname"
                           name="nickname"
                           placeholder="닉네임을 입력하세요"
                           required>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-2">
                    <button type="submit" id="submitBtn" class="btn btn-primary w-75 mb-3">회원가입</button>
                    <a href="../index/index.php" class="btn btn-dark w-75 mb-3">홈으로</a>
                </div>

                <div class="text-center">
                    <a href="Login.php" class="text-decoration-none">이미 계정이 있으신가요? 로그인</a>
                </div>

            </form>
            <div class="mt-2">
                <input type="checkbox" class="form-check-input">
                <span>대충 개인정보 이용동의</span>
            </div>
        </div>
    </div>
</section>

<script>
    /** 비밀번호 유효성 검사*/
    const submitBtn = $('#submitBtn')
    submitBtn.on('click', (e) => {
        const isChecked = $('input[type="checkbox"]').is(':checked');

        if(!isChecked) {
            e.preventDefault();
            alert("개인정보 이용동의 체크 해주세요!")
            return;
        }

        const psss = $('#password').val();
        const psssOk = $('#passwordOk').val();
        if(psss == ''){
            $('#alert').text("비밀번호를 입력해주세요.");
            return;
        }
        else if(psss !== psssOk) {
            $('#alert').text("비밀번호가 맞지 않습니다.");
            // e.preventDefault();
            return;
        }else{
            $('#SignUpForm').submit();
        }
    })

    /** 아이디 중복 체크*/
    const checkId = $("#checkId")
    checkId.on('click', (e) => {
        const $userId = $("#userId").val();
        if($userId === "") {
            alert("아이디를 입력해주세요.");
            $("#userId").focus();
            return;
        }else{
            $.ajax({
                url: "Auth_ok.php",
                method: "POST",
                data: {
                    userId: $userId,
                    WhatIsForm: '3',
                },
                dataType: 'json',
                success:(r)=>{
                    if(r.empty === 'empty'){
                        alert("사용 가능한 아이디 입니다.");
                    }else{
                        alert("이미 있는 아이디 입니다.");
                    }
                }
            })
        }
    })
</script>

<?php
include("../../inc/footer.php");
?>