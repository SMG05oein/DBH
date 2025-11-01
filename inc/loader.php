<?php
//namespace DBH\inc;
include_once "../../config/connect.php";
$user_id = isset($_COOKIE["user_id"])? $_COOKIE["user_id"]:0;
$LOCATIONINDEX = '/DBH/src/index/index.php';
/**
 * 로그인 함?
 */
function isLogin(){
    global $user_id;
    $sql = "SELECT * FROM dbh.members WHERE user_id='$user_id'";
    $row = O($sql);
    return $row === true ? true : false; //값이 없으면(로그인을 안 했다면) 참
}
/**
 * ***********************
 * 이 파일은 유용한 함수들을 정의한 클래스 파일입니다.
 * 이 파일에 대한 수정을 새로운 함수 정의에 대해서는 자유롭지만
 * 수정에 대해서는 자기가 만든 함수가 아니면 금지합니다.
 * ***********************
 * */

class loader
{
}

//////////////////////////// DB 관련 함수들 공통으로 사용 //////////////////////////////

/**
 * print_r을 보기 좋게 바꾼 것
 * */
function rr(){
    @header("Content-Type: text/html; charset=UTF-8");
    $args= func_get_args();
    foreach ($args as $v) {
        print "<pre class='MyPre'>";
        print_r($v);
        print "</pre>";
    }
}

/**
 * INSERT 함수
 * */
function INSERT($table, $data, $debug='N'){
    if(!$table || !$data){
        return false;
    }

    global $mysqli;

//        $sql = "INSERT INTO dbh.members (user_id, user_pass, user_name,
//                         nickname, birth, fk_department_code,
//                         reg_date, last_login_date, login_yn)
//                VALUES ('20242083', '!!!!', '서민관', 'SMG', null,null,NOW(),null,0)";

    $sql = "INSERT INTO ".$table.'(';
    $cnt = 0;

    /* 컬럼 이름 작업 */
    foreach($data as $k=>$v){
        $sql .= ($cnt==0) ? $k : ','.$k;
        $cnt++;
    }

    $sql .= ') VALUES (';
    $cnt = 0;

    /* 컬럼에 넣을 데이터 작업 */
    foreach($data as $k=>$v){
        if(strtoupper($v)=='GETDATE()' || strtoupper($v)=='NOW()'){
            $sql .= ($cnt==0) ? 'now()' : ',now()';
        }
        else if($v== null){
            $sql .= ($cnt==0) ? 'null' : ',null';
        }
        else{
            $sql .= ($cnt==0) ? "'$v'" : ",'$v'";
        }
        $cnt++;
    }
    $sql .= ')';

    if($debug=='Y'){
        rr($sql); exit;
    }
    $result = $mysqli->query($sql);
    if ($result === TRUE) {
//        echo "새로운 레코드가 성공적으로 생성되었습니다.";
//        echo
//        "<script>
//                console.log('안녕');
//            </script>";
        return false;

    } else {
        echo "오류: " . $sql . "<br>" . $mysqli->error;
        return true;
    }
}

/**
 * UPDATE 함수
 * */
function UPDATE($table, $data, $where, $debug='N'){
    if(!$table || !$data){
        return false;
    }

    global $mysqli;

    $sql = "";

    $sql .= "UPDATE ".$table;
    $sql .= " SET ";
    $cnt = 0;
    foreach($data as $k=>$v){
        $sql .= $cnt>0 ? " , " : ' ';

        if(strtoupper($v)=='GETDATE()' || strtoupper($v)=='NOW()'){
            $sql .= $k. "=now()";
        } else{
            $sql .= $k."='$v'";
        }
        $cnt ++;
    }

    $sql .= " WHERE ";
    $cnt = 0;
    $arr_list = array('>=','<=','>','<',);
    foreach($where as $k2=>$v2){
        $sql .= $cnt>0 ? " AND  " : ' ';
        if(is_array($v2)){
            /*foreach($v2 as $item)*/
            $sql .= $k2." in ('".implode("','",$v2)."')";
        }
        else{
            $is_true = false;
            if(strpos($v2,'>') !== false || strpos($v2,'<') !== false){
                foreach($arr_list as $item){
                    if((strpos($v2,$item) !== false) && $is_true == false){
                        $text = str_replace($item,'',$v2);
                        $sql .= $k2." {$item} '".($text)."'";
                        $is_true = true;
                    }
                }
            }else if(strpos($v2,'BETWEEN') !== false){
                $sql .= $k2." ".$v2." ";
                $is_true = true;
            }
            if($is_true == false)
                $sql .= $k2."='".($v2)."'";
        }
        $cnt ++;
    }

    $sql =str_replace("'GETDATE()'",'getdate()',$sql);
    $sql =str_replace("'NOW()'",'getdate()',$sql);

    if($debug=='Y'){
        rr($sql); exit;
    }
    $result = $mysqli->query($sql);

    if ($result === TRUE) {
//        echo "레코드가 성공적으로 업데이트되었습니다.";
        return false;

    } else {
        echo "오류: " . $mysqli->error;
        return true;
    }
}

/**
 * DELETE 함수
 * */
function DEL($table, $where, $debug='N'){
    if(!$table || !$where){
        return false;
    }

    global $mysqli;

    $sql = "DELETE FROM ".$table;
    $sql .= " WHERE ";
    $cnt = 0;
    foreach($where as $k2=>$v2){
        $sql .= $cnt>0 ? " AND  " : ' ';
        if(is_array($v2)){
            $sql .= $k2." in ('".implode("','",$v2)."')";
        }
        else{
            $sql .= $k2."='".($v2)."'";
        }
        $cnt ++;
    }
    if($debug=='Y'){
        rr($sql); exit;
    }
    $result=$mysqli->query($sql);

    if ($result === TRUE) {
//        echo "레코드가 성공적으로 삭제되었습니다.";
        return false;
    } else {
        echo "오류: " . $mysqli->error;
        return true;
    }
}

/**
 * Select One
 * */
function O($sql, $bind = array(), $debug='N'){
    global $mysqli;

    $where = " ";
    $cnt = 0;
    if($bind != null){
        $where = " WHERE ";
        foreach($bind as $k=>$v){
            $where .= ($cnt==0)? $k."='$v'" : "AND $k"."='$v'";
            $cnt++;
        }
    }
    $sql .= $where;

    if($debug=='Y'){
        rr($sql); exit;
    }

    $result = $mysqli->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            return $row;
        }
    }else{
        return true;
    }
}

/**
 * Select All
 * */
function A($sql, $bind = array(), $debug='N'){
    global $mysqli;

    $where = " ";
    $cnt = 0;
    if($bind != null){
        $where = " WHERE ";
        foreach($bind as $k=>$v){
            $where .= ($cnt==0)? $k."='$v'" : "AND $k"."='$v'";
            $cnt++;
        }
    }
    $sql .= $where;

    if($debug=='Y'){
        rr($sql); exit;
    }

    $result = $mysqli->query($sql);
    $resultRow = array();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $resultRow[] = $row;
        }
    }else{
        return true;
    }

    return $resultRow;
}

/**
 * 페이징 하는거
 * 함수 파라미터 기본값 변경 후 푸시하는 금지
 *
 * -인자설명-
 * 1. sql문
 * 2. 바인딩 값
 * 3. 한 번에 가져올 데이터 개수
 * 4. SQL문법인데 정렬 기준입니다. 사용 예시는 알려드리겠습니다.
 * 5. 디버그 할거야?
 * ------------------------
 * 이후 인자는 함수 호출 시에 값을 넣지 마세요.
 * 6. 페이징 할 때 밑에 몇 개 보여? -> 이해 안 되면 7로 바꿔보세요.
 * 7. 함수 이름
 * ------------------------
 */
function PAGE($sql, $bind = array(), $pagesu = 10, $orderby="", $debug='N', $pagelength = 5, $page_func = 'PAGE_ADMIN')
{
    // 전체 데이터 수
    $cnt = CNT($sql, $bind);

    // get 값으로 전달 되는 현재 페이지 넘버 |현재 페이지|
    $page_no = (isset($_GET['p']) && $_GET['p']) ? $_GET['p'] : 1;

    // pagesu 도 get 값 우선 |몇 개 가져옴?|
//    $pagesu = (isset($_GET['pagesu']) && $_GET['pagesu']) ? $_GET['pagesu'] : $pagesu;

    // 데이터 뽑아 오는 시작 데이터 넘버
    $start = ( $pagesu * ($page_no - 1 ) ) +1;
    $end  = $pagesu * $page_no;

    $sql = "
	        SELECT * 
	        FROM (
	            SELECT *, ROW_NUMBER() OVER ( ". $orderby ." ) AS ROW_NUM
                FROM ( " . $sql . " ) PAGE1
	        ) PAGE2	       
            WHERE ROW_NUM BETWEEN $start AND $end ";

    //echo $sql;
    $rows = A($sql, $bind, $debug);

    list($navi,$page_count) = $page_func($pagesu, $pagelength, $cnt, $page_no);

    // 데이터 넘버링
//    if ($rows) {
//        $row_start = $cnt - (($page_no -1) *  $pagesu);
//        foreach ($rows as &$v) {
//            $v['order_'] = $row_start;
//            $row_start--;
//        }
//    }
    return array($rows,$cnt,$navi,$page_count);
}
/* 예
$sql = "select * from sms_template order by idx desc";
list($rows,$cnt,$navi,$page_count) = PAGE($sql,array(),10,10,'PAGE_ADMIN');
rr($rows);
rr($cnt);
rr($navi);
rr($page_count);
*/

/**
 * GetRow 함수를 짧게
 */
function CNT()
{

    list( $sql, $bind)= split_arg(func_get_args());
    $neo_sql= sprintf("select count(*) as cnt from ( %s ) cnt_table", $sql);
    $row = O($neo_sql, $bind);
    return $row['cnt'];
}

/**
 * 여기서만 쓰이는 놈임. array를 타이핑하기 싫어서 추가한 함수임
 */
function split_arg($a)
{
    $sql= $a[0];
    if (count($a)>=1) {
        array_shift($a);
    }

    if (isset($a[0])&& is_array($a[0])) {
        $a= $a[0];
    }
    return array( $sql, $a);
}

/**
 * 페이징 하는거 html 만들기
 */
function PAGE_ADMIN($pagesu = 5, $pagelength = 10, $cnt = 0, $page_no = 1)
{
    // 페이징 전체 데이터
    $total_page_cnt = ceil($cnt/$pagesu);

    $redirect_url = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];

    $navi = '';
    $navi .=
        '<div id="pagination"> 
	        <ul class="pagination">';
    if ($total_page_cnt>0) {
        // 0. 현재 페이지가 몇번째 그룹인지
        $current = ceil($page_no / $pagelength);
        $end = ($current * $pagelength) ;
        $start = $end - $pagelength +1 ;
        if ($end>$total_page_cnt) {
            $end = $total_page_cnt;
        }
        if ($start<=0) {
            $start = 1;
        }
        $next = $end + 1 ;
        $prev = $start - $pagelength ;

        /*
        rr("current:".$current);
        rr("start:".$start);
        rr("end:".$end);
        rr("prev:".$prev);
        rr("next:".$next);
        */

        // 1. << 버튼 정보
        if ($page_no>$pagelength) {
            $myPage = $start - 1;
            $navi .= "<li class=\"page-item previous \"><a class=\"page-link\" href='$redirect_url?".reset_get('p')."&p=$myPage'><i class=\"previous\"></i><</A></li>";
        }else{
            $navi .= ("<li class=\"page-item previous disabled\"><a class=\"page-link\" href='javascript:void(0)'><i class=\"previous\"></i><</A></li>");
        }

        // 2. 페이지 네비 만들기
        for ($i = $start; $i<=$end; $i++) {
            $active = "";
            if ($page_no==$i) {
                $active = "active";
                $navi .= '<li class="page-item active"><a href=\'javascript:void(0)\' class="page-link">'.$i.'</a></li>';
            }else{
                $navi .= '<li class="page-item "><a href="'.$redirect_url."?".reset_get('p')."&p=".$i.'" data-page="'.$i.'" class="page-link">'.$i.'</A></li>';
            }

        }

        // 3. >> 버튼 정보
        if ($total_page_cnt>=$next) {
            $myPage = $end+1;
//            $pagesu += isset($_GET['p'])? $_GET['pagesu']:'';
            $navi .= "<li class=\"page-item next\"><a class=\"page-link\" href='$redirect_url?".reset_get('p')."&p=$myPage'><i class=\"next\"></i>></a></li>";
            //$navi .= '<a href="'.$redirect_url."?".reset_get('page_no')."&p=".$total_page_cnt.'" data-page="'.$total_page_cnt.'" class="blocks">&gt;&gt;</a>';
        }else{
            $navi .= ("<li class=\"page-item next disabled\"><a class=\"page-link\" href='javascript:void(0)'><i class=\"next\">></i></a></li>");
        }
    }

    $navi .= '</ul></div>';
    $page_count = '';//'<span class="loader-pagecount">'.$page_no."/".$total_page_cnt.' page</span>';
    return array($navi,$page_count);
}

/**
 * Reset GET
 *
 * $_GET 으로 넘오어는 변수들 중 인자로 넘어오는 문자열을 제외한 키와 값들을 하나의 문자열로 만들어줌
 */
function reset_GET()
{
    $re= array();
    $args= func_get_args();
    $args[]= '_url';
    foreach ($_GET as $k => $v) {
        if (!in_array($k, $args)) {
            if (is_array($v)) {
                foreach ($v as $vv) {
                    $re[]= $k.'[]='.urlencode($vv);
                }
            } else {
                $re[]= $k.'='.urlencode($v);
            }
        }
    }


    return implode('&', $re);
}