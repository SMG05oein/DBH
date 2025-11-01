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

$sql = "SELECT b.*, m.user_name FROM board b
        inner join members m on b.fk_member_id = m.member_id";
$orderBy = 'ORDER BY board_id DESC';

list($rows,$cnt,$navi) = PAGE($sql, '' , 10, $orderBy, '');
//rr($rows);
//rr($cnt);

$cnt = $cnt - (($p-1) * 10);

//echo $navi;
?>

<!--Begin Content-->
<section>
    <!--Begin Search-->
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
        <!--End Search-->

        <!--Begin Board-->
        <div class="row">
            <div class="col-12 py-4 bg-white border rounded shadow-sm">
                <div class="d-flex justify-content-end mb-3">
                    <a href="../Board/board_write.php?<?=reset_GET('p')."&p=$p"?>" class="btn btn-primary btn-sm">게시글 등록</a>
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
                            <?php foreach ($rows as $row):
                                $board_id = $row['board_id'];
                                $sql = "SELECT * FROM board_categories 
                                        inner join categories on category_id = fk_category_id
                                        WHERE fk_board_id = $board_id ";
                                $Crow=A($sql, '' , '');

//                                rr($Crow);
                                $category_names = array_column($Crow, 'category_name');
                                $category = implode(', ', $category_names);


//                                rr($Crow);
                                ?>
                                <tr>
                                    <td class="text-center"><?=$cnt?></td>
                                    <td class="text-center"><?=$category?>  </td>
                                    <td class="text-start"><a href="../Board/board_write.php?board_id=<?=$board_id?>&toIndex=1" class="text-decoration-none text-primary fw-bold"><?=$row['title']?></td></a>
                                    <td class="text-center"><?=$row['user_name']?></td>
                                    <td class="text-center"><?=substr($row['reg_date'],0,10)?></td>
                                    <td class="text-center"><?=$row['hits']?:0?></td>
                                </tr>
                            <?php $cnt--; endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center pt-2">
                        <?= $navi;?>
                    </div>
                </div>
            </div>
        </div>
        <!--End Board-->
    </div>
</section>
<!--End Content-->

<?php
//// @add 2014-01-24
//$p_link = $_SERVER['QUERY_STRING'];
//
//$p_link = preg_replace("/p=[0-9]+&/", "", $p_link);
//$p_link = preg_replace("/(&?p=\d+)/", "", $p_link);
//
//$per_block		= 10;
//$total_block	= 0;
//$block			= 10;
//
//if(isset($total_page))
//{
//    $total_block=ceil($total_page/$per_block);
//}
//else
//{
//    $total_page	= 0;
//}
//if(isset($p))
//{
//    $block=ceil($p/$per_block);
//}
//else
//{
//    $p	= 1;
//}
//$first_page=($block-1) * $per_block;
//$last_page=$block * $per_block;
//
//if($total_block <= $block) $last_page=$total_page;
//
//echo ("
//<!-- paging -->
//<div id=\"pagination\">
//	<ul class=\"pagination\">
//");
//
//if($block > 1){
//    $myPage=$first_page;
//    echo ("<li class=\"page-item previous \"><A HREF=\"$_SERVER[PHP_SELF]?p=$myPage&$p_link\" class=\"page-link\"><i class=\"previous\"></i></A></li>");
//}else{
//    echo ("<li class=\"page-item previous disabled\"><a class=\"page-link\" href='javascript:void(0)'><i class=\"previous\"></i></A></li>");
//}
//
//
//for($DirectPage= $first_page + 1 ; $DirectPage <= $last_page ; $DirectPage++){
//    if($p == $DirectPage){
//        echo ("<li class=\"page-item active\"><a href='javascript:void(0)' class=\"page-link\">$DirectPage</a></li>");
//    }else{
//        echo ("<li class=\"page-item \"><A HREF=\"$_SERVER[PHP_SELF]?p=$DirectPage&$p_link\" class=\"page-link\">$DirectPage</A></li>");
//    }
//}
//
//if($block < $total_block){
//    $myPage=$last_page + 1;
//    echo ("<li class=\"page-item next\"><A HREF=\"$_SERVER[PHP_SELF]?p=$myPage&$p_link\" class=\"page-link\"><i class=\"next\"></i></A></li>");
//}else{
//    echo ("<li class=\"page-item next disabled\"><a class=\"page-link\" href='javascript:void(0)'><i class=\"next\"></i></a></li>");
//}
//
//echo ("</ul></div>
//<!-- paging* -->");
//?>

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


