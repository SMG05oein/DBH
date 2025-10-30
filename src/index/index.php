<?php
/*
 * 각 파일을 만들 때는 이 파일 처럼 헤더와 푸터 그리고 loader를 불러온 후
 * 그 사이에 섹션을 통해 작업 공간을 만든 후에 작업해 주세요
 * Head, GNB 등등을 상대경로로 지정을 해놔서 웹 페이지 만들 때 가능하면 폴더 만들고 그 안에 작업해주세요
 * */
include("../../inc/head.php");

$keyFiled = isset($_GET['keyFiled']) ? $_GET['keyFiled'] : "";
$keyWord = isset($_GET['keyWord']) ? $_GET['keyWord'] : "";

?>

<!--Begin Content-->
<section>
    <div class="container my-5">
        <div class="row justify-content-center mb-4">
            <div class="col-12">
                <header class="d-flex justify-content-between align-items-center pb-3 border-bottom border-3">
                    <h4 class="fs-4 fw-bold text-primary mb-0">게시판</h4>

                    <form method="get" class="d-flex align-items-center">
                        <div class="d-flex gap-2">
                            <select name="keyFiled" class="form-select form-select-sm w-auto">
                                <option value="">분류</option>
                                <option value="title" <?=$keyFiled == "title"? "selected":""?>>제목</option>
                                <option value="category" <?=$keyFiled == "category"? "selected":""?>>카테고리</option>
                            </select>
                            <input type="text" name="keyWord" class="form-control form-control-sm" placeholder="검색어를 입력하세요">
                            <button class="btn btn-dark btn-sm d-flex align-items-center">검색</button>
                        </div>
                    </form>
                </header>
            </div>
        </div>

        <div class="row">
            <div class="col-12 py-4 bg-light border rounded">

                <div class="d-flex justify-content-end mb-3">
                    <a href="#" class="btn btn-primary">게시글 등록</a>
                </div>

                <div class="py-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 8%;">NO</th>
                                <th class="text-center" style="width: 15%;">카테고리</th>
                                <th class="text-center">제목</th>
                                <th class="text-center" style="width: 12%;">작성자</th>
                                <th class="text-center" style="width: 15%;">작성일</th>
                                <th class="text-center" style="width: 10%;">조회</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">공지</td>
                                <td><a href="#" class="text-decoration-none text-primary">공지사항입니다. 꼭 확인하세요!</a></td>
                                <td class="text-center">관리자</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">150</td>
                            </tr>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">공지</td>
                                <td><a href="#" class="text-decoration-none text-primary">공지사항입니다. 꼭 확인하세요!</a></td>
                                <td class="text-center">관리자</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">150</td>
                            </tr>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">공지</td>
                                <td><a href="#" class="text-decoration-none text-primary">공지사항입니다. 꼭 확인하세요!</a></td>
                                <td class="text-center">관리자</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">150</td>
                            </tr>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">공지</td>
                                <td><a href="#" class="text-decoration-none text-primary">공지사항입니다. 꼭 확인하세요!</a></td>
                                <td class="text-center">관리자</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">150</td>
                            </tr>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">자유</td>
                                <td><a href="#" class="text-decoration-none text-primary">새로 리뉴얼된 게시판이 깔끔하네요.</a></td>
                                <td class="text-center">홍길동</td>
                                <td class="text-center">2025.10.29</td>
                                <td class="text-center">45</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Content-->

<?php
include("../../inc/footer.php");
?>


