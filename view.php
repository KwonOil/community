<?php
session_start();
require_once("db.php");

// 로그인한 사람만 접근 가능
if (!isset($_SESSION['member_id'])) {
    echo "로그인이 필요합니다.";
    exit();
}

// post_id 검증
if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    echo "잘못된 접근입니다.";
    exit();
}

$post_id = (int)$_GET['post_id'];

$query = "SELECT p.post_id, p.post_title, p.post_content, p.insert_date, m.nickname
        FROM tbl_post p
        JOIN tbl_member m ON p.member_id = m.member_id
        WHERE p.post_id = ?";
$post_data = db_select($query, [$post_id]);

if (!$post_data || count($post_data) === 0) {
    echo "글을 찾을 수 없습니다.";
    exit();
}

$post = $post_data[0];
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($post['post_title']) ?> - 글 상세보기</title>
    <style>
        body { max-width: 700px; margin: 30px auto; font-family: Arial, sans-serif; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 10px; }
        .date { color: #777; margin-bottom: 20px; }
        .content { white-space: pre-wrap; font-size: 16px; line-height: 1.5; }
        a.button {
            display: inline-block; margin-top: 20px; text-decoration: none; padding: 6px 12px;
            background: #337ab7; color: white; border-radius: 4px; margin-right: 10px;
        }
        form { display: inline; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function post_delete(post_id) {
        if (!confirm("정말 삭제하시겠습니까?")) return;

        $.post("delete.api.php", { post_id: post_id })
        .done(function(result) {
            alert(result.msg);
            if (result.result) {
                // 성공 시 해당 글의 <tr> 삭제
                $('button[onclick="post_delete(' + post_id + ')"]').closest('tr').remove();
                window.location.href = "main_page.php";
            }
        });
    }
    </script>
</head>
<body>
    <!-- 제목 -->
    <h1><?= htmlspecialchars($post['post_title']) ?></h1>
    <!-- 작성일 -->
    <div class="date">작성일: <?= htmlspecialchars($post['insert_date']) ?></div>
    <!-- 내용 -->
    <div class="content"><?= nl2br(htmlspecialchars($post['post_content'])) ?></div>
    <!-- 돌아가기 -->
    <a href="main_page.php" class="button">목록으로 돌아가기</a>
    <!-- 수정하기 -->
    <a href="edit.post.php?post_id=<?= $post['post_id'] ?>" class="button">수정하기</a>
    <!-- 삭제하기 -->
    <button onclick="post_delete(<?= $post['post_id'] ?>)" style="background:#d9534f; border:none; color:#fff; padding:5px 10px; cursor:pointer;">
        삭제하기
    </button>
</body>
</html>
