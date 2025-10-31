<?php
/*
 * 각 파일을 만들 때는 이 파일 처럼 헤더와 푸터 그리고 loader를 불러온 후
 * 그 사이에 섹션을 통해 작업 공간을 만든 후에 작업해 주세요
 * Head, GNB 등등을 상대경로로 지정을 해놔서 웹 페이지 만들 때 가능하면 폴더 만들고 그 안에 작업해주세요
 * */
include("../../inc/head.php");

$p = isset($_GET['p']) ? $_GET['p'] : 1;
$keyFiled = isset($_GET['keyFiled']) ? $_GET['keyFiled'] : "";
$keyWord = isset($_GET['keyWord']) ? $_GET['keyWord'] : "";

$sql = "SELECT * FROM dbh.members";
$orderBy = '';

list($rows,$cnt,$navi) = PAGE($sql, '' , 1, $orderBy, '');
//rr($rows);
//rr($cnt);

//echo $navi;
?>

<!--Begin Content-->
<section>
    <div class="container my-5">
        <div class="row justify-content-center mb-4">
            <div class="col-12">
                <header class="d-flex justify-content-between align-items-center pb-3 border-bottom border-3">
                    <h4 class="fs-4 fw-bold text-primary mb-0">게시판</h4>

                    <form method="get" class="d-flex align-items-center">
                        <input type="hidden" value="<?=$p?>" name="p">
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
            <div class="col-12 py-4 bg-white border rounded shadow-sm">
                <div class="d-flex justify-content-end mb-3">
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square me-1"></i> 게시글 등록
                    </a>
                </div>

                <div class="py-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-light">
                            <tr class="fw-bold">
                                <th class="text-center" style="width: 8%;">NO</th>
                                <th class="text-center" style="width: 15%;">카테고리</th>
                                <th class="text-start">제목</th> <th class="text-center" style="width: 12%;">작성자</th>
                                <th class="text-center" style="width: 15%;">작성일</th>
                                <th class="text-center" style="width: 10%;">조회</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">공지</td>
                                <td class="text-start"><a href="#" class="text-decoration-none text-dark fw-bold">공지사항입니다. 꼭 확인하세요!</a></td>
                                <td class="text-center">관리자</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">150</td>
                            </tr>
                            <tr>
                                <td class="text-center">9</td>
                                <td class="text-center">공지</td>
                                <td class="text-start"><a href="#" class="text-decoration-none text-dark fw-bold">중요한 변경 사항에 대한 안내</a></td>
                                <td class="text-center">관리자</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">150</td>
                            </tr>
                            <tr>
                                <td class="text-center">8</td>
                                <td class="text-center">자유</td>
                                <td class="text-start"><a href="#" class="text-decoration-none text-dark">날씨가 많이 쌀쌀해졌네요. 감기 조심하세요!</a></td>
                                <td class="text-center">사용자A</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">98</td>
                            </tr>
                            <tr>
                                <td class="text-center">7</td>
                                <td class="text-center">질문</td>
                                <td class="text-start"><a href="#" class="text-decoration-none text-dark">PHP에서 세션을 관리하는 가장 좋은 방법은 무엇인가요?</a></td>
                                <td class="text-center">질문러</td>
                                <td class="text-center">2025.10.30</td>
                                <td class="text-center">21</td>
                            </tr>
                            <tr>
                                <td class="text-center">6</td>
                                <td class="text-center">자유</td>
                                <td class="text-start"><a href="#" class="text-decoration-none text-dark">새로 리뉴얼된 게시판이 깔끔하네요.</a></td>
                                <td class="text-center">홍길동</td>
                                <td class="text-center">2025.10.29</td>
                                <td class="text-center">45</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center pt-2">
                        <?= $navi;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Content-->

<?php
// @add 2014-01-24
$p_link = $_SERVER['QUERY_STRING'];

$p_link = preg_replace("/p=[0-9]+&/", "", $p_link);
$p_link = preg_replace("/(&?p=\d+)/", "", $p_link);

$per_block		= 10;
$total_block	= 0;
$block			= 10;

if(isset($total_page))
{
    $total_block=ceil($total_page/$per_block);
}
else
{
    $total_page	= 0;
}
if(isset($p))
{
    $block=ceil($p/$per_block);
}
else
{
    $p	= 1;
}
$first_page=($block-1) * $per_block;
$last_page=$block * $per_block;

if($total_block <= $block) $last_page=$total_page;

echo ("
<!-- paging -->
<div id=\"pagination\"> 
	<ul class=\"pagination\">
");

if($block > 1){
    $myPage=$first_page;
    echo ("<li class=\"page-item previous \"><A HREF=\"$_SERVER[PHP_SELF]?p=$myPage&$p_link\" class=\"page-link\"><i class=\"previous\"></i></A></li>");
}else{
    echo ("<li class=\"page-item previous disabled\"><a class=\"page-link\" href='javascript:void(0)'><i class=\"previous\"></i></A></li>");
}


for($DirectPage= $first_page + 1 ; $DirectPage <= $last_page ; $DirectPage++){
    if($p == $DirectPage){
        echo ("<li class=\"page-item active\"><a href='javascript:void(0)' class=\"page-link\">$DirectPage</a></li>");
    }else{
        echo ("<li class=\"page-item \"><A HREF=\"$_SERVER[PHP_SELF]?p=$DirectPage&$p_link\" class=\"page-link\">$DirectPage</A></li>");
    }
}

if($block < $total_block){
    $myPage=$last_page + 1;
    echo ("<li class=\"page-item next\"><A HREF=\"$_SERVER[PHP_SELF]?p=$myPage&$p_link\" class=\"page-link\"><i class=\"next\"></i></A></li>");
}else{
    echo ("<li class=\"page-item next disabled\"><a class=\"page-link\" href='javascript:void(0)'><i class=\"next\"></i></a></li>");
}

echo ("</ul></div>
<!-- paging* -->");
?>

<!--<ul class="pagination">
	<li class="page-item previous disabled"><a href="#" class="page-link"><i class="previous"></i></a></li>
	<li class="page-item "><a href="#" class="page-link">1</a></li>
	<li class="page-item active"><a href="#" class="page-link">2</a></li>
	<li class="page-item "><a href="#" class="page-link">3</a></li>
	<li class="page-item "><a href="#" class="page-link">4</a></li>
	<li class="page-item "><a href="#" class="page-link">5</a></li>
	<li class="page-item "><a href="#" class="page-link">6</a></li>
	<li class="page-item next"><a href="#"  class="page-link"><i class="next"></i></a></li>
</ul>-->

<?php
include("../../inc/footer.php");
?>


