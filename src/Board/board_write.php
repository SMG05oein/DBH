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
                WHERE board_id = ?";
        $bind = array('board_id' => $board_id);
        $board_row = O($sql, $bind , '');

        $hits = $board_row['hits'] + 1; //트리거 만들어서 해도 될 듯?
        UPDATE('board', array('hits' => $hits), array('board_id' => $board_id));

        $sql = "SELECT b.*, a.*, IF(a.end_date < CURRENT_DATE(), 0, 1) as tempVal 
                FROM board b
                inner join activity a on a.activity_id = b.fk_activity_id
                WHERE b.board_id = ? ";
        $bind = array('board_id' => $board_id);
        $Trow=O($sql, $bind);
//        rr($Trow);

        $board_id_SQL = "SELECT fk_activity_id, title 
                         FROM board
                         WHERE fk_activity_id = ? AND (isDiffSelect IS NULL OR isDiffSelect != 1)";
        $bind = array('fk_activity_id' => $Trow['fk_activity_id']);
        $board_id_ROW=A($board_id_SQL, $bind, '');
//        rr($board_id_ROW);

        $sql = "SELECT * FROM board_categories 
                inner join categories on category_id = fk_category_id
                WHERE fk_board_id = ? ";
        $bind = array('fk_board_id' => $board_id);
        $Crow=A($sql, $bind , '');
//        rr($Crow);

        // $sql = "SELECT COUNT(*) FROM personnel WHERE fk_activity_id = ?";            //37으로 수정했음
        $sql = "SELECT * FROM personnel WHERE fk_activity_id = ?";
        $bind = array('fk_activity_id'=>$Trow['activity_id']);
        $count = CNT($sql, $bind);

        /*          begin           */
        $isApplied = false; // 기본값: 신청 안함
        $my_member_id = 0;
        $dbStatus = $Trow['status'];

        if($user_id) { // 로그인이 되어 있다면  
            // 1. 내 고유 member_id 찾기
            $sql = "SELECT member_id FROM members WHERE user_id = ?";
            $bind = array('user_id' => $user_id);
            $my_member_row = O($sql, $bind);

            if($my_member_row) {
                $my_member_id = $my_member_row['member_id'];

                // 2. personnel 테이블에서 내가 신청했는지 확인
                $sql = "SELECT * FROM personnel WHERE fk_activity_id = ? AND fk_member_id = ?";
                $bind = array('fk_activity_id' => $Trow['activity_id'], 'fk_member_id' => $my_member_id);
                $apply_cnt = CNT($sql, $bind);
                if($apply_cnt > 0) $isApplied = true;
            }
        }
        /*           end           */
    }
    $category_sql = "SELECT * FROM dbh.categories";
    $category_rows = A($category_sql);
    $userEqWriter = '';

    $activitySql =
    "
    SELECT b.title, b.board_id, b.fk_activity_id, b.isDiffSelect
        FROM board b
        INNER JOIN activity a on b.fk_activity_id = a.activity_id
        INNER JOIN members m on m.member_id = b.fk_member_id
    WHERE m.user_id = ? AND (b.isDiffSelect != 1 OR b.isDiffSelect IS NULL)
    ";

    $bind = array('user_id' => $user_id);
    $activity_rows = A($activitySql, $bind, '');
//    rr($activity_rows);

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
                    <input type="hidden" name="board_id" value="<?=$board_row['board_id']?>">
                    <input type="hidden" name="activity_id" value="<?=$Trow['activity_id']?>">
                    <div class="mb-3">
                        <label for="postTitle" class="form-label fw-semibold d-flex">
                            <div>제목</div>
                            <div class="flex-end ms-auto" style="display: <?=isset($board_id)?'block':'none'?>">조회수: <?=$board_row['hits'] + 1?></div>
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
                    <?php if(!$userEqWriter){?>
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
                    <?php }?>

                    <div>
                        <label class="form-label fw-semibold d-flex justify-content-center text-center">카테고리 목록</label>
                        <div id="categoryDIV" class="d-flex gap-2">
                            <?php if(isset($Crow)){foreach($Crow as $row):?>
                            <div class="category-item-container d-flex align-items-center gap-2 border rounded p-1 ps-2">
                                <!--어... name속성 제거-->
                                <input type='hidden' class="category_id" name='' id="category_id" value='<?=isset($row['category_id']) ? $row['category_id'] : ''?>'>
                                <div class="fw-semibold"><?=isset($row['category_name']) ? $row['category_name'] : ''?></div>
                                <?php if(!$userEqWriter){?>
                                <button type="button" class="btn btn-danger btn-sm delete-category-btn">삭제</button>
                                <?php }?>
                            </div>
                            <?php endforeach;}?>
                        </div>
                    </div>

                    <div class="mb-3 mt-2 border-1 border-top ">
                        <label for="postContent" class="mt-1 form-label fw-semibold d-flex justify-content-center text-center">활동등록</label>
                        <?php
                        $tempVal = $board_id_ROW[0]['fk_activity_id'] ?? false;
                        if(!$userEqWriter){?>
                        <select id="MySelect" name="MySelect" class="form-select form-select-sm">
                            <option value="">새 활동</option>
                            <?php foreach($activity_rows as $tempRow):?>
                            <option value="<?= $tempRow['fk_activity_id'] . ',' . $tempRow['board_id']?>" <?=($tempRow['isDiffSelect'] != 1)&&($tempRow['fk_activity_id']==$tempVal)? 'selected' : '' ?> >
                                <?= $tempRow['title']?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <?php }?>
                        <div class="row pt-2">
                            <div class="col-md-4 mb-3">
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

                        <!-- <?php if(isset($board_id)){?>                                      // 이전코드 주석처리함
                        <div class="d-flex justify-content-center align-content-center gap-2">
                            <div>( <?=$count?> / <?=$Trow['max_personnel']?> )</div>
                            <button type="button" class="btn btn-sm btn-primary">신청</button>
                        </div>
                        <?php }?> -->

                        <!--                begin                   -->

                        <?php if(isset($board_id)){?>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-3 p-3 border rounded bg-light">
                            <div class="fw-bold fs-5">
                                참여 현황 : <span id="currentCount"><?=$count?></span> / <?=$Trow['max_personnel']?>
                            </div>
                            
                            <a href="participants.php?activity_id=<?= $Trow['activity_id'] ?>&board_id=<?= $board_id ?>"
                            class="btn btn-info btn-sm">
                            참여자 명단 보기
                            </a>


                            <?php
                            // 1. 로그인을 안 했을 때
                            if(!$user_id) { 
                            ?>
                                <span class="text-danger small ms-2 fw-bold">* 로그인 후 신청 가능합니다.</span>
                            
                            <?php
                            // 2. 작성자 본인일 때 
                            } else if(!$Trow['tempVal']){echo '';}
                            else if(!$userEqWriter) {
                                if($dbStatus == 3 || $dbStatus == '취소') {
                            ?>
                                    <span class="badge bg-danger p-2">취소된 활동입니다</span>
                                <?php 
                                } else { 
                                // 아직 취소 안 했으면 [활동 취소] 버튼 표시 (누르면 status -> 3)
                                ?>
                                    <button type="button" id="statusCancelBtn"
                                    class="btn btn-warning btn-sm"
                                    data-activity-id="<?=$Trow['activity_id']?>">
                                    활동 취소
                                    </button>
                            <?php 
                                }    
                            // 3. 이미 신청한 사람일 때 (취소 버튼)
                            } else if($isApplied) { 
                            ?>
                                <button type="button" id="applyBtn"
                                        class="btn btn-danger btn-sm"
                                        data-activity-id="<?=$Trow['activity_id']?>"
                                        data-status="cancel">
                                    신청 취소
                                </button>
                            
                            <?php 
                            // 4. 그 외 (신청 가능)
                            } else { 
                            ?>
                                <button type="button" id="applyBtn"
                                        class="btn btn-primary btn-sm"
                                        data-activity-id="<?=$Trow['activity_id']?>"
                                        data-status="apply">
                                    참여 신청
                                </button>
                            <?php } ?>
                            </div>
                        <?php }?>
                        <!--                end                  -->
                    
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a onclick="location.href='<?=$LOCATIONINDEX?>'" class="btn btn-secondary">나가기</a>
                        
                        <!-- 이전 코드 주석처리  -->
                        <!-- <?php if(!$userEqWriter){?>
                        <button type="submit" class="btn btn-primary" id="submitBtn"><?=isset($board_id)? "수정" : "등록"?></button>
                        <?php }?> -->
                        
                        <!-- begin  -->
                        <?php if(!$userEqWriter){ ?>
                            <?php if(isset($board_id)){ ?>
                                <button type="button" class="btn btn-danger" id="deleteBtn">삭제</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">수정</button>
                            <?php } else if(!isset($board_id)) { ?>
                                <button type="submit" class="btn btn-primary" id="submitBtn">등록</button>
                            <?php }
                        } ?>
                        <!-- end -->
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
        if ($(`input[id='category_id'][value='${categoryId}']`).length > 0) {
            alert('이미 등록된 카테고리입니다.');
            return;
        }
        const newCategoryItem =
            `
            <div class="category-item-container bg-success d-flex align-items-center gap-2 border rounded p-1 ps-2">
                <input type='hidden' name='category_id[]' id='category_id' value='${categoryId}'>
                <div class="fw-semibold">${category}</div>
                <button type="button" class="btn btn-danger btn-sm delete-category-btn">삭제</button>
            </div>
            `;

        console.log(category);
        $('#categoryDIV').append(newCategoryItem);

        $('#category').val('');
    })

    $('#categoryDIV').on('click', '.delete-category-btn', function() {
        const temp =$(this).closest('.category-item-container');
        const categoryId = temp.find('.category_id').val();
        if(categoryId === undefined) {
            $(this).closest('.category-item-container').remove();
            return;
        }
        if($('.delete-category-btn').length == 1) {
            alert('카테고리는 한 개 이상 존재해야 합니다.');
            return;
        }
        console.log(categoryId);
        $.ajax({
            url: './board_ok.php',
            type: 'POST',
            dataType: 'json',
            data: {
                checkForm: 3,
                board_id: <?= isset($_GET['board_id'])?$_GET['board_id']:0 ?>,
                categoryId: categoryId
            },success: function(response) {
                if (response && response.result === 'success') {
                    // alert('도서 항목이 성공적으로 삭제되었습니다.');
                    $(this).closest('.category-item-container').remove()
                } else {
                    // 서버 오류 메시지 출력
                    alert('삭제 실패: ' + (response ? response.message : '서버 응답 오류'));
                }
            },
            error: function(xhr, status, error) {
                alert('통신 오류: 서버 연결 실패.');
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    });

    $('#submitBtn').on('click', function(e){
        e.preventDefault();
        const categoryCount = $('#categoryDIV').find("input[name='category_id[]']").length;
        const categoryCount2 = $('#categoryDIV').find("input[id='category_id']").length;

        if (categoryCount === 0 && categoryCount2 === 0) {
            alert("게시글에 최소한 1개 이상의 카테고리를 등록해야 합니다.");
            return;
        }
        const selectedActivity = $('#MySelect').val();
        if (selectedActivity === '') {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const memberCount = $('#memberCount').val();

            if (!startDate || !endDate || !memberCount || memberCount <= 0) {
                alert("새 활동을 등록하려면 시작일, 종료일, 인원수를 모두 입력해야 합니다.");
                return;
            }
        }
        $('#asdf').submit();
    })

    /*             begin               */
    // 활동 신청/취소 버튼 클릭 이벤트
    $('#applyBtn').on('click', function() {
        const btn = $(this);
        const activityId = btn.data('activity-id');
        const currentStatus = btn.data('status'); // 'apply' 또는 'cancel'
        const actionType = (currentStatus === 'apply') ? 'register' : 'cancel';

        if(!confirm(actionType === 'register' ? '활동에 참여하시겠습니까?' : '참여를 취소하시겠습니까?')){
            return;
        }

        $.ajax({
            url: './board_ok.php',
            type: 'POST',
            dataType: 'json',
            data: {
                checkForm: 'activity_application', // board_ok.php에서 처리할 구분자
                activity_id: activityId,
                action_type: actionType // 신청인지 취소인지 구분
            },
            success: function(res) {
                if (res.result === 'success') {
                    alert(res.message);
                    location.reload(); // 화면 새로고침하여 버튼 상태 및 인원수 갱신
                } else {
                    alert('처리 실패: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                alert('통신 오류 발생');
                console.error(xhr.responseText);
            }
        });
    });

    $('#deleteBtn').on('click', function() {
        if(confirm('정말 이 게시글을 `삭제하시겠습니까?\n연결된 활동 및 참여 정보가 모두 삭제됩니다.')) {
            
            const form = $('#asdf'); // 폼 ID 확인

            // 1. 기존 checkForm 값 제거 (혹시 몰라서)
            form.find('input[name="checkForm"]').remove();

            // 2. 삭제용 checkForm 값(4)을 가진 hidden input 생성 및 추가
            $('<input>').attr({
                type: 'hidden',
                name: 'checkForm',
                value: '4'
            }).appendTo(form);

            // 3. 폼 제출 (board_ok.php로 이동)
            form.submit();
        }
    });

    //작성자의 활동 취소(status=='취소') 버튼 클릭 
    $('#statusCancelBtn').on('click', function() {
        if(!confirm('정말 이 활동을 취소하시겠습니까?\n상태가 [취소]로 변경되며 더 이상 신청을 받을 수 없습니다.')){
            return;
        }

        const activityId = $(this).data('activity-id');

        $.ajax({
            url: './board_ok.php',
            type: 'POST',
            dataType: 'json',
            data: {
                checkForm: 'activity_application',
                activity_id: activityId,
                action_type: 'activity_cancel' 
            },
            success: function(res) {
                if (res.result === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('처리 실패: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                alert('통신 오류 발생');
            }
        });
    });
    /*            end               */
</script>

<script>
    function resetMySelect(){
        $('#MySelect').val('');
    }

    const currentActivityId = <?= $Trow['activity_id'] ?? 0 ?>;
    const tempValFromDB = <?= $tempVal ?? 0 ?>;

    $('#MySelect').on('change', function(e){
        const selectedValue = e.target.value;
        const isNewActivity = (selectedValue == '');
        const isCurrentActivity = (selectedValue == currentActivityId);

        if(isNewActivity || isCurrentActivity){
            $('#startDate').prop('disabled', false);
            $('#endDate').prop('disabled', false);
            $('#memberCount').prop('disabled', false);
        } else {
            $('#startDate').prop('disabled', true);
            $('#endDate').prop('disabled', true);
            $('#memberCount').prop('disabled', true);
        }
    });

    // $('#startDate').on('change', function(e) {
    //     resetMySelect();
    // })
    // $('#endDate').on('change', function(e) {
    //     resetMySelect();
    // })
    // $('#memberCount').on('change', function(e) {
        //     resetMySelect();
        // })
    </script>
<?php
include("../../inc/footer.php");
?>
