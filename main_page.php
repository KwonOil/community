<?php
session_start();

// 로그인 여부 확인
if (!isset($_SESSION['member_id'])) {
    header("Location: /");
    exit();
}

// DB 작업
require_once("db.php");

$member_id = $_SESSION['member_id'];
$post_query =
"SELECT p.post_id, p.post_title, p.post_content, p.member_id, p.insert_date, m.nickname
FROM tbl_post p
JOIN tbl_member m ON p.member_id = m.member_id
ORDER BY p.insert_date DESC";
$post_data = db_select($post_query);
if (!is_array($post_data)) {
    $post_data = []; // 빈 배열로 대체
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <title>게시판 목록</title>
<!---------------------------------- 스크립트 ----------------------------------->
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

                // 번호 재정렬
                $('#postTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        });
    }

    // HTML 이스케이프 함수
    function escapeHtml(str) {
        return $('<div>').text(str).html();
    }
    </script>
<!---------------------------------- 스타일 ------------------------------------->
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        form { margin-top: 30px; }
        .post_title {
            text-decoration: none;
        }
        .post_title:link{
            color : black;
        }
        .post_title:visited{
            color : purple;
        }
        .post_title:hover{
            color : blue;
        }
        .post_title:active{
            color : red;
        }
    </style>

</head>
<body>
<?php require_once("header.php"); ?>
<h1>📋 게시판 목록 📋</h1>

<!-- 글 작성 폼 -->
<form method="POST" action="write.post.php">
    <p>
        <input type="text" name="post_title" maxlength="50" placeholder="제목 (50자)" style="width:80%; padding:8px;" autocomplete = "off" required>
    </p>
    <p>
        <textarea name="post_content" maxlength="500" placeholder="내용(500자)" style="width:96%; height:100px; padding:8px;" autocomplete = "off" required></textarea>
    </p>
    <p>
        <input type="submit" value="글 작성">
    </p>
</form>

<!-- 게시글 목록 테이블 -->
<table id = "postTable">
    <col width = "60px">
    <col>
    <col width = "100px">
    <col width = "120px">

    <thead>
        <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach($post_data as $post): ?>
            <tr>
                <!-- 번호 -->
                <td><?= $i++ ?></td>
                <!-- 제목 -->
                <td>
                    <a class = "post_title" href="view.php?post_id=<?= htmlspecialchars($post['post_id']) ?>">
                        <?= htmlspecialchars($post['post_title']) ?>
                    </a>
                    <?php if (($post['member_id'] == $_SESSION['member_id']) || $_SESSION['member_id'] === 1): ?>
                        <button onclick="post_delete(<?= $post['post_id'] ?>)">삭제</button>
                    <?php endif; ?>
                </td>
                <!-- 작성자 -->
                <td><?= htmlspecialchars($post['nickname']) ?></td>
                <!-- 작성일 -->
                <td><?= htmlspecialchars($post['insert_date']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>

</html>