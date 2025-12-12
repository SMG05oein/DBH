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
    $MySelect = $_POST['MySelect'];
    $MySelect = explode(',', $MySelect);

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
    if($MySelect[0] != ''){
        $activity_id = $MySelect[0];
        $temp_data = array(
            'fk_activity_id' => $MySelect[0],
            'isDiffSelect' => 1
        );
        $temp_where = array(
            'board_id' => $board_id,
        );
        UPDATE($board_table, $temp_data, $temp_where, '');
    }else{
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $member_count = $_POST['member_count'];
        if($start_date == '' || $end_date == '' || $member_count == ''){
            echo"
            <script>
            alert('새 활동을 선택하셨으나 활동에 필요한 필수 데이터가 누락되었습니다.');
            history.back();
            </script>
            ";
            DEL($board_table, array('board_id' => $board_id));
        }else{
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
        }
    }


    echo "<script>location.href='/DBH/src/Board/board_write.php?board_id=$board_id'</script>";
}else if($checkForm == 2){ // 게시글 수정
    $MySelect = $_POST['MySelect'];
    $MySelect = explode(',', $MySelect);
//    rr($MySelect, $_POST['board_id'], $_POST['activity_id']);
//    exit;
    // $sql = "SELECT * FROM $board_table WHERE fk_activity_id = '$MySelect' ";
    // $row = CNT($sql);
    // rr($row);
    // exit; //

    $sql = "SELECT * FROM members WHERE user_id = '$user_id' ";
    $row = O($sql);
    $member_id = $row['member_id'];

    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'] ?? array();
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $member_count = $_POST['member_count'];
    $board_id = $_POST['board_id'];
    $activity_id = $_POST['activity_id'];

//    rr($_POST);
//    exit;

    /** start 제목, 내용 수정 board */
    if($MySelect[0] != ''){
        $temp_data = array(
            'title' => $title,
            'content' => $content,
            'fk_activity_id' => $MySelect[0],
            'isDiffSelect' => ($MySelect[0] == $activity_id && $MySelect[1] == $board_id) ? 2 : 1
        );
        $temp_where = array(
            'board_id' => $board_id,
        );
        UPDATE($board_table, $temp_data, $temp_where, '');
        /** end 제목, 내용 수정 board */

        /** start 카테고리 수정 categories */

        foreach ($category_id as $r) {
            $data = array(
                'fk_board_id' => $board_id,
                'fk_category_id' => $r,
            );
            INSERT($board_category_table, $data, '');
        }
        /** end 카테고리 수정 categories */
        if ($MySelect[0] == $activity_id) {
        /** start 활동 수정 activity */
            $activity_data = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'max_personnel' => $member_count,
                'status' => 1,
            );
            $activity_where = array(
                'activity_id' => $MySelect[0],
            );
            UPDATE($activity_table, $activity_data, $activity_where, '');
        }
        /** end 활동 수정 activity */
    }else{
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
            'isDiffSelect' => 2
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
    }

//    exit;
    // 성공 메시지 후 리다이렉트
    echo "<script>alert('게시글이 성공적으로 수정되었습니다.'); location.href='/DBH/src/Board/board_write.php?board_id=$board_id'</script>";
    }else if($checkForm == 3){ //카테고리 삭제
        header('Content-Type: application/json');
        $board_id = $_POST['board_id'];
        $categoryId = $_POST['categoryId'];

        // 현재 카테고리 개수 확인
    $sql = "SELECT COUNT(*) cnt FROM board_categories WHERE fk_board_id = ?";
    $cnt = O($sql, ['fk_board_id' => $board_id]);

    if ($cnt['cnt'] <= 1) {
        echo json_encode([
            'message' => '카테고리는 최소 1개 이상 필요합니다.'
        ]);
        exit;
    }

    $where = array(
        'fk_board_id'=>$board_id,
        'fk_category_id'=>$categoryId,
    );
    DEL($board_category_table, $where, '');
    $r = ['result'=>'success'];
    echo json_encode($r);
    exit;
}else if($checkForm == 4){ //게시글 삭제
    $board_id = $_POST['board_id'];
    $where = array(
        'board_id'=>$board_id,
    );
    DEL($board_table, $where, '');
    echo "<script>location.href='$LOCATIONINDEX'</script>";

}

/*       begin         */
else if($checkForm == 'activity_application') { // 활동 신청 및 취소 로직

    header('Content-Type: application/json');

    // 로그인 유저 확인
    $user_id = isset($_COOKIE["user_id"]) ? $_COOKIE["user_id"] : '';
    if(!$user_id) {
        echo json_encode(['result'=>'fail', 'message'=>'로그인이 필요합니다.']);
        exit;
    }

    // user_id로 member_id(PK) 찾기
    $sql = "SELECT member_id FROM members WHERE user_id = '$user_id'";
    $row = O($sql);
    if(!$row) {
        echo json_encode(['result'=>'fail', 'message'=>'회원 정보를 찾을 수 없습니다.']);
        exit;
    }
    $member_id = $row['member_id'];

    // 데이터 받기
    $activity_id = $_POST['activity_id'];
    $action_type = $_POST['action_type'];

    // 활동 정보 조회 (상태 검증용)
    // status+0 as status : ENUM을 숫자로 가져오기 (1:모집중, 2:마감, 3:취소, 4:기간만료)
    $sql = "SELECT max_personnel, status+0 as status, end_date FROM $activity_table WHERE activity_id = '$activity_id'";
    $act_row = O($sql);


    if($action_type == 'register') {
        // A. 날짜 마감(기간만료) 체크 (DB 조작 포함)
        $today = date("Y-m-d"); 
        if($act_row['end_date'] < $today) {
            // 날짜가 지났는데 DB 상태가 아직 '기간만료(4)'가 아니라면 업데이트
            if($act_row['status'] != 4) {
                $update_data = array('status' => 4); 
                $update_where = array('activity_id' => $activity_id);
                UPDATE($activity_table, $update_data, $update_where, '');
            }
            echo json_encode(['result'=>'fail', 'message'=>'신청 기간이 지난 활동입니다.']);
            exit;
        }

        // B. 모집 상태(status) 체크 (1:모집중이 아니면 거절)
        if($act_row['status'] != 1) { 
             $msg = '신청할 수 없는 상태입니다.';
             if($act_row['status'] == 2) $msg = '이미 마감된 활동입니다.';
             if($act_row['status'] == 3) $msg = '취소된 활동입니다.';
             if($act_row['status'] == 4) $msg = '신청 기간이 지난 활동입니다.'; 

             echo json_encode(['result'=>'fail', 'message'=>$msg]);
             exit;
        }

        // C. 현재 인원 및 중복 신청 확인
        $sql = "SELECT * FROM $personnel_table WHERE fk_activity_id = '$activity_id'";
        $current_cnt = CNT($sql);

        // 정원 초과 확인
        if($current_cnt >= $act_row['max_personnel']) {
            echo json_encode(['result'=>'fail', 'message'=>'정원이 초과되었습니다.']);
            exit;
        }

        // 중복 신청 확인
        $sql = "SELECT * FROM $personnel_table WHERE fk_activity_id = '$activity_id' AND fk_member_id = '$member_id'";
        $dup_cnt = CNT($sql);
        if($dup_cnt > 0) {
            echo json_encode(['result'=>'fail', 'message'=>'이미 신청하셨습니다.']);
            exit;
        }

        // D. [최종] INSERT 실행
        $data = array(
            'fk_activity_id' => $activity_id,
            'fk_member_id' => $member_id,
            'join_date' => 'NOW()'
        );
        INSERT($personnel_table, $data, '');
        
        // E. 자동 마감 처리 (신청 후 인원이 꽉 찼다면 상태를 2:마감 으로 변경)
        if($current_cnt + 1 >= $act_row['max_personnel']) {
            $update_data = array('status' => 2); // 2: 마감
            $update_where = array('activity_id' => $activity_id);
            UPDATE($activity_table, $update_data, $update_where, '');
        }

        echo json_encode(['result'=>'success', 'message'=>'참여 신청이 완료되었습니다.']);

    } else if ($action_type == 'cancel') {

        $where = array(
            'fk_activity_id' => $activity_id,
            'fk_member_id' => $member_id
        );
        DEL($personnel_table, $where, '');
        
        // 취소로 인해 자리가 비게 되었을 때 처리
        // 만약 현재 상태가 '마감(2)'이었다면 -> 다시 '모집중(1)'으로 변경
        // (단, 기간만료(4)나 취소(3) 상태라면 건드리지 않음)
        if($act_row['status'] == 2) {
            $update_data = array('status' => 1); // 1: 모집중
            $update_where = array('activity_id' => $activity_id);
            UPDATE($activity_table, $update_data, $update_where, '');
        }

        echo json_encode(['result'=>'success', 'message'=>'참여가 취소되었습니다.']);
    } else if ($action_type == 'activity_cancel') { 
        /** 작성자의 활동 취소 로직(Status -> 3) */

        // 상태를 3(취소)으로 변경
        $update_data = array('status' => 3); 
        $update_where = array('activity_id' => $activity_id);
        UPDATE($activity_table, $update_data, $update_where, '');

        echo json_encode(['result'=>'success', 'message'=>'활동이 취소 처리되었습니다.']);
    }  

    exit;
}
/*         end           */


?>    