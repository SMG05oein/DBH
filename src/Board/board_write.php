<?php
include("../../inc/head.php");
$toIndex = isset($_GET["toIndex"]) || false;
$user_id = isset($_COOKIE["user_id"])? $_COOKIE["user_id"]:0;

if(isLogin() && !$toIndex){
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='/DBH/src/Members/Login.php'</script>";
}else{
    $disabled = 'disabled';
//    echo $board_id;
    if(isset($_GET['board_id'])){
        $board_id = isset($_GET['board_id']) ? $_GET['board_id'] : '';
        $sql = "SELECT b.*, m.user_id FROM board b
                inner join members m on m.member_id = b.fk_member_id
                WHERE board_id = $board_id ";
        $board_row = O($sql, '' , '');
        $hits = $board_row['hits'] + 1; //트리거 만들어서 해도 될 듯?
        UPDATE('board', array('hits' => $hits), array('board_id' => $board_id));
        $sql = "SELECT * FROM board
                inner join activity on activity_id = fk_activity_id
                WHERE board_id = $board_id ";
        $Trow=O($sql, '' , '');
        $sql = "SELECT * FROM board_categories 
                inner join categories on category_id = fk_category_id
                WHERE fk_board_id = $board_id ";
        $Crow=A($sql, '' , '');
//        rr($Crow);
    }
    $category_sql = "SELECT * FROM dbh.categories";
    $category_rows = A($category_sql);
    $userEqWriter = '';
    if(!isset($_GET['board_id'])) {
//        echo 'sss';
        $userEqWriter = 0;
    }
    else{
        $userEqWriter = (isset($_GET['board_id'])) && ($user_id != $board_row['user_id']);
    //    echo (($user_id == ($board_row['user_id'])) || !isset($board_row['user_id']));
    //    echo $user_id . ' '. $board_row['user_id'].' '.'<br>';
//        echo 'ss'.$userEqWriter;
//        echo 'aa'.isset($_GET['board_id']);
//        echo $user_id == $board_row['user_id'];
    }

}

?>

<!--Begin Content-->
<section>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <h2 class="mb-4 fw-bold text-center">게시글 등록</h2>

                <form action="board_ok.php" method="POST" id="asdf" class="border border-1 p-3 rounded shadow-sm">
                    <input type="hidden" name="checkForm" value="<?=isset($board_row['board_id'])? 2 : 1?>">
                    <div class="mb-3">
                        <label for="postTitle" class="form-label fw-semibold d-flex">
                            <div>제목</div>
                            <div class="flex-end ms-auto" style="display: <?=isset($board_id)?'block':'none'?>">조회수: <?=$board_row['hits']?></div>
                        </label>
                        <input type="text" <?=$userEqWriter? $disabled: ''?>
                               class="form-control"
                               id="postTitle"
                               name="title"
                               placeholder="제목을 입력하세요."
                               value="<?=isset($board_row['title'])?$board_row['title']:''?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="postContent" class="form-label fw-semibold">내용</label>
                        <textarea class="form-control" <?=$userEqWriter? $disabled: ''?>
                                  id="postContent"
                                  name="content"
                                  rows="4"
                                  placeholder="게시글 내용을 입력하세요."
                                  required><?=isset($board_row['content'])?$board_row['content']:''?></textarea>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <label for="category" class="form-label fw-semibold d-flex justify-content-center text-center">카테고리</label>
                        <select class="form-select w-50 d-flex" name="category" id="category">
                            <option value="">분류</option>
                            <?php foreach($category_rows as $row):?>
                            <option value="<?=$row['category_id']?>"><?=$row['category_name']?></option>
                            <?php endforeach;?>
                        </select>
                        <button type="button" id="categoryBtn" class="btn btn-outline-primary">카테고리 등록</button>
                    </div>
                    <div>
                        <label class="form-label fw-semibold d-flex justify-content-center text-center">카테고리 목록</label>
                        <div id="categoryDIV" class="d-flex gap-2">
                            <?php if(isset($Crow)){foreach($Crow as $row):?>
                            <div class="category-item-container d-flex align-items-center gap-2 border rounded p-1 ps-2">
                                <!--어... name속성 제거-->
                                <input type='hidden' name='' id="category_id[]" value='<?=isset($row['category_id']) ? $row['category_id'] : ''?>'>
                                <div class="fw-semibold"><?=isset($row['category_name']) ? $row['category_name'] : ''?></div>
                                <button type="button" class="btn btn-danger btn-sm delete-category-btn">삭제</button>
                            </div>
                            <?php endforeach;}?>
                        </div>
                    </div>

                    <div class="mb-3 mt-2 border-1 border-top ">
                        <label for="postContent" class="mt-1 form-label fw-semibold d-flex justify-content-center text-center">활동등록</label>
                        <div class="row pt-2"> <div class="col-md-4 mb-3">
                                <label for="startDate" class="form-label">시작일</label>
                                <input type="date" <?=$userEqWriter? $disabled: ''?>
                                       class="form-control"
                                       id="startDate"
                                       name="start_date"
                                       value="<?=isset($Trow['start_date']) ? $Trow['start_date'] : ''?>"
                                       required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="endDate" class="form-label">종료일</label>
                                <input type="date" <?=$userEqWriter? $disabled: ''?>
                                       class="form-control"
                                       id="endDate"
                                       name="end_date"
                                       value="<?=isset($Trow['end_date']) ? $Trow['end_date']:''?>"
                                       required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="memberCount" class="form-label">인원수</label>
                                <input type="number" <?=$userEqWriter? $disabled: ''?>
                                       class="form-control"
                                       id="memberCount"
                                       name="member_count"
                                       placeholder="최대 인원"
                                       min="1"
                                       value="<?=isset($Trow['max_personnel']) ? $Trow['max_personnel']:''?>"
                                       required>
                            </div>
                        </div>
                        <?php if(isset($board_id)){?>
                        <div class="d-flex justify-content-center align-content-center gap-2">
                            <div>( / )</div>
                            <button type="button" class="btn btn-sm btn-primary">신청</button>
                        </div>
                        <?php }?>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a onclick="location.href='<?=$LOCATIONINDEX?>'" class="btn btn-secondary">취소</a>
                        <?php if(!$userEqWriter){?>
                        <button type="submit" class="btn btn-primary" id="submitBtn"><?=isset($board_id)? "수정" : "등록"?></button>
                        <?php }?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
<!--End Content-->
<script>
    $('#categoryBtn').on('click',function(){
        const category = $('#category option:selected').text();
        const categoryId = $('#category').val();

        if (!categoryId) {
            alert('카테고리를 선택해주세요.');
            return;
        }
        if ($(`input[id='category_id[]'][value='${categoryId}']`).length > 0) {
            alert('이미 등록된 카테고리입니다.');
            return;
        }
        const newCategoryItem =
            `
            <div class="category-item-container d-flex align-items-center gap-2 border rounded p-1 ps-2">
                <input type='hidden' name='category_id[]' id='[category_id' value='${categoryId}'>
                <div class="fw-semibold">${category}</div>
                <button type="button" class="btn btn-danger btn-sm delete-category-btn">삭제</button>
            </div>
            `;

        console.log(category);
        $('#categoryDIV').append(newCategoryItem);

        $('#category').val('');
    })

    $('#categoryDIV').on('click', '.delete-category-btn', function() {
        $(this).closest('.category-item-container').remove();
    });

    $('#submitBtn').on('click', function(e){
        e.preventDefault();
        const categoryCount = $('#categoryDIV').find("input[name='category_id[]']").length;

        if (categoryCount === 0) {
            alert("게시글에 최소한 1개 이상의 카테고리를 등록해야 합니다.");
            return; // 제출 중단
        }
        $('#asdf').submit();
    })
</script>
<?php
include("../../inc/footer.php");
?>
