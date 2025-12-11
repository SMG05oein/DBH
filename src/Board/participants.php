<?php
include("../../inc/head.php");

// activity_id 받기
$activity_id = isset($_GET['activity_id']) ? $_GET['activity_id'] : '';

if (!$activity_id) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 참여자 목록 조회
$sql = "
    SELECT m.nickname, m.phone, d.department_name, p.join_date FROM personnel p
    INNER JOIN members m ON m.member_id = p.fk_member_id
    LEFT JOIN departments d ON d.department_code = m.fk_department_code
    WHERE p.fk_activity_id = ?
    ORDER BY p.join_date ASC
";
$bind = array($activity_id);
$rows = A($sql, $bind);
?>

<section class="container my-5">
    <h2 class="fw-bold text-center mb-4">참여자 명단</h2>

    <div class="card p-4 shadow">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>닉네임</th>
                    <th>전화번호</th>
                    <th>학과</th>
                    <th>신청일</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows && is_array($rows)) { ?>
                    <?php foreach ($rows as $r) { ?>
                        <tr>
                            <td><?= $r['nickname'] ?></td>
                            <td><?= $r['phone'] ?></td>
                            <td><?= $r['department_name'] ?></td>
                            <td><?= $r['join_date'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">참여자가 없습니다.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-end mt-3">
            <a href="board_write.php?board_id=<?= $_GET['board_id'] ?>" class="btn btn-secondary">돌아가기</a>
        </div>
    </div>
</section>

<?php include("../../inc/footer.php"); ?>
