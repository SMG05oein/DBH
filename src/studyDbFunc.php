<?php
include("../inc/head.php");
include "../inc/loader.php";

/**Begin INSERT*/
$table = "dbh.members";
$data = array(
    'user_id' => '20242083',
    'user_pass' => '!!!!',
    'user_name' => '서민관',
    'nickname' => 'SMG',
    'birth' => 'NOW()',
    'fk_department_code' => null,
    'reg_date' => 'NOW()',
    'last_login_date' => 'NOW()',
    'login_yn' => '0'
);

//INSERT($table, $data, 'Y');
/**End INSERT*/

/**Begin UPDATE*/
$where = array(
    'user_id' => '20242083',
    'nickname'=>'SMG'
);
$data2 = array(
    'user_name' => '김아무개',
    'user_pass' => '1234'
);
//UPDATE($table, $data2, $where, 'Y');
/**End UPDATE*/

/**Begin DEL*/
//DEL($table, $where, 'Y');
/**End DEL*/

/**Begin O and A*/
$sql = "SELECT * FROM dbh.members";
//$sql = "SELECT * FROM dbh.members Where user_name like '%서민관%'";
$bind = array(
//    'user_pass' => '!!!!',
);
//$row = O($sql, $bind, 'Y');
//$rows = A($sql, $bind, 'Y');
/**Begin O and A*/

?>

<section>
    <div>
        <div>
            <div class="mb-2 border border-1 border-dark">
                <h5 >0 번째: PHP개요</h5>
                <div class="mb-3">C나 JAVA랑 문법 거의 똑같음. 변수 선언 $변수명;</div>
            </div>
            <div>

                <h5 class="text-info">첫 번째: SELECT 관련 함수 사용 법</h5>
                <div class="mb-2 border border-1 border-dark">
                    <div>O($sql, $bind, $debug) Select One 반환값이 1차원 배열, 가장 위 첫 번째 데이터를 가져옴</div>
                    <div>A($sql, $bind, $debug) Select All 반환값이 2차원 배열</div>
                </div>
                <div class="mb-2 border border-1 border-dark">
                    <div class="mb-2 ">O, A함수 인자 소개</div>
                    <div class="mb-2">$sql -> 말 그대로 SQL문 작성 하면 됩니다.</div>
                    <div class="mb-2">사용법: $sql= "SELECT * FROM dbh.members WHERE member_id = '19'";</div>
                    <div class="mb-2">$bind -> WHERE문을 $sql에 직접 작성하지 않고 따로 빼서 관리하고 싶을 때 사용합니다.</div>
                    <div class="mb-2">
                        $sql= "SELECT * FROM dbh.members ";<br>
                        $bind = array('member_id' => '19');
                        <br/>
                        !주의! 함수에 $bind이 null이 아니면 where절이 붙음. <br/>
                        그래서 Where절을 직접 붙이고 싶으면 '' 또는 $bind가 null인지 확인해주세요.
                    </div>
                    <div class="mb-2">$debug -> 말 그대로 debug, $sql, $bind가 다 적용된 SQL문을 반환합니다.</div>
                </div>

                <h5 class="text-success">두 번째: INSERT함수 사용 법</h5>
                <div class="mb-2 border border-1 border-dark">
                    <div>INSERT($table, $data, $debug) DB에 데이터를 삽입합니다.</div>
                </div>
                <div class="mb-2 border border-1 border-dark">
                    <div class="mb-2 ">INSERT함수 인자 소개</div>
                    <div class="mb-2">$table -> table이름 작성해주시면 됩니다.</div>
                    <div class="mb-2">$data -> 어느 컬럼에 어떤 데이터를 넣을 지 결정합니다.</div>
                    <pre class="mb-2">사용법:
                        $data = array(
                            'user_id' => '20242083',
                            'user_pass' => '!!!!',
                            'user_name' => '서민관',
                            'nickname' => 'SMG',
                            'birth' => 'NOW()',
                            'fk_department_code' => null,
                            'reg_date' => 'NOW()',
                            'last_login_date' => 'NOW()',
                            'login_yn' => '0'
                        );
                    </pre>
                </div>

                <h5 class="text-primary">세 번째: UPDATE함수 사용 법</h5>
                <div class="mb-2 border border-1 border-dark">
                    <div>UPDATE($table, $data, $where, $debug) DB에 데이터를 업데이트 합니다.</div>
                </div>
                <div class="mb-2 border border-1 border-dark">
                    <div class="mb-2">중복된 건 넘어감.</div>
                    <div class="mb-2">$where -> 쿼리문에 WHERE 조건에 들어갈 컬럼과 값을 결정합니다</div>
                    <div class="mb-2">사용법: $where = array('member_id' => '19');</div>
                </div>

                <h5 class="text-danger  ">네 번째: DEL함수 사용 법</h5>
                <div class="mb-2 border border-1 border-dark">
                    <div>DEL($table, $where, $debug) DB에 데이터를 삭제합니다.</div>
                </div>
                <div class="mb-2 border border-1 border-dark">
                    <div class="mb-2">중복된 건 넘어감.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include("../inc/footer.php");
?>
