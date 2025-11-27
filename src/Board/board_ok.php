<?php
include '../../inc/loader.php';

//rr($_POST);
$checkForm = $_POST['checkForm'];
$board_table = "board";
$board_category_table = 'board_categories';
$activity_table = "activity";
$personnel_table = "personnel";

if($checkForm == '1'){ //게시글 등록

    /**
     * 로직 설명
     * 1. 제목이랑 내용은 From POST요청으로 넘어온 걸 처리함 $user_id는 loader.php에 있음
     * 2. 게시글 등록
     * 3. 게시글을 등록했기에 board_id를 얻어올 수 있음
     * 4. 카테고리는 배열로 넘어와서 foreach문으로 처리
     * 5. activity 처리
     * 6. activity처리 후 id가 생성되므로 그 아이디를 board의 fk_activity_id에 넣기
     * 7. 나 자신을 personnel 테이블에 넣기
     */

    /** Begin 1,2 */
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "SELECT * FROM members WHERE user_id = '$user_id' ";
    $row = O($sql);
    $member_id = $row['member_id'];

    $board_data = array(
        'fk_member_id' => $row['member_id'],
        'title' => $title,
        'content' => $content,
        'real_yn' => 1,
        'hits' => 0,
    );
    INSERT($board_table, $board_data, '');
    /** End 1,2 */;

    /** Begin 3 */
    $sql = "SELECT * FROM $board_table ORDER BY board_id DESC";
    $row = O($sql);
    $board_id = $row['board_id'];
//    echo $row['board_id'];
    /** End 3 */

    /** Begin 4 */
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : array();
    foreach ($category_id as $r) {
//        echo $r;
        $data = array(
            'fk_board_id' => $board_id,
            'fk_category_id' => $r,
        );
        INSERT($board_category_table, $data, '');
    }
    /** End 4 */

    /** Begin 5 */
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $member_count = $_POST['member_count'];

    $activity_data = array(
        'start_date' => $start_date,
        'end_date' => $end_date,
        'max_personnel' => $member_count,
        'status' => 1,
    );
    INSERT($activity_table, $activity_data, '');
    /** End 5 */

    /** Begin 6 */
    $sql = "SELECT * FROM $activity_table ORDER BY activity_id DESC";
    $row = O($sql);
    $activity_id = $row['activity_id'];
    $temp_data = array(
        'fk_activity_id' => $activity_id,
    );
    $temp_where = array(
        'board_id' => $board_id,
    );
    UPDATE($board_table, $temp_data, $temp_where, '');
    /** End 6 */

    /** Begin 7 */
    $personnel_data = array(
        'fk_activity_id' => $activity_id,
        'fk_member_id' => $member_id,
    );
    INSERT($personnel_table, $personnel_data, '');
    /** End 7 */


    echo "<script>location.href='/DBH/src/Board/board_write.php?board_id=$board_id'</script>";
}else if($checkForm == 2){ //게시글 수정
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : array();
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $member_count = $_POST['member_count'];
    $board_id = $_POST['board_id'];
    $activity_id = $_POST['activity_id'];
//    rr($_POST);
//    exit;
    /** start 제목, 내용 수정 board */
    $temp_data = array(
        'title' => $title,
        'content' => $content,
    );
    $temp_where = array(
        'board_id' => $board_id,
    );
    UPDATE($board_table, $temp_data, $temp_where, '');
    /** end 제목, 내용 수정 board */

    /** start 카테고리 수정(근데 입력이긴 함) categories */
    foreach ($category_id as $r) {
        $data = array(
            'fk_board_id' => $board_id,
            'fk_category_id' => $r,
        );
        INSERT($board_category_table, $data, '');
    }
    /** end 카테고리 수정 categories */

    /** start 활동 수정 activity */
    $activity_data = array(
        'start_date' => $start_date,
        'end_date' => $end_date,
        'max_personnel' => $member_count,
        'status' => 1,
    );
    $activity_where = array(
        'activity_id' => $activity_id,
    );
    UPDATE($activity_table, $activity_data, $activity_where, '');
    /** end 활동 수정 activity */


    echo "<script>location.href='/DBH/src/Board/board_write.php?board_id=$board_id'</script>";

}else if($checkForm == 3){ //카테고리 삭제
    header('Content-Type: application/json');
    $board_id = $_POST['board_id'];
    $categoryId = $_POST['categoryId'];
    $where = array(
        'fk_board_id'=>$board_id,
        'fk_category_id'=>$categoryId,
    );
    DEL($board_category_table, $where, '');
    $r = ['result'=>'success'];
    echo json_encode($r);
    exit;
}

?>