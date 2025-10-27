<?php
//namespace DBH\inc;
include "../config/connect.php";

/*
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

/*
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

/*
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
        echo "새로운 레코드가 성공적으로 생성되었습니다.";
        echo
        "<script>
                console.log('안녕');
            </script>";
    } else {
        echo "오류: " . $sql . "<br>" . $mysqli->error;
    }
    return false;
}

/*
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
        }
        else{
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
        echo "레코드가 성공적으로 업데이트되었습니다.";
    } else {
        echo "오류: " . $mysqli->error;
    }

    return false;
}

/*
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
        echo "레코드가 성공적으로 삭제되었습니다.";
    } else {
        echo "오류: " . $mysqli->error;
    }

    return false;
}

/*
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
        return "빈 값";
    }
}

/*
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
        return "빈 값";
    }

    return $resultRow;
}