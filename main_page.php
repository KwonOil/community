
<?php
session_start();
// 로그인 여부 확인
if (!isset($_SESSION['member_id'])) {
    header("Location: /");
    exit();
}
require_once("db.php");

$member_id = $_SESSION['member_id'];
$post_query =
"SELECT p.post_id, p.post_title, p.post_content, p.member_id, p.insert_date, m.nickname
FROM tbl_post p
JOIN tbl_member m ON p.member_id = m.member_id
ORDER BY p.insert_date DESC
LIMIT 10";
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
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        form { margin-top: 30px; }
        .post_title {
            text-decoration: none;
        }
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
                // offset 값 조정
                offset--;

                // 번호 재정렬
                $('#postTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                // 새로운 글 1개 추가로 가져와서 목록 유지
                $.post("main_page.api.php", { offset: offset, limit: 1 })
                .done(function(result) {
                    if (result.result && result.post_data.length > 0) {
                        const post = result.post_data[0];
                        const isMyPost = post.member_id == <?= $_SESSION['member_id'] ?>;
                        const tbody = $('#postTable tbody');
                        const index = tbody.find('tr').length;
                        let newRow = '<tr>';
                        newRow += '<td>' + (index + 1) + '</td>';
                        newRow += '<td><a class="post_title" href="view.php?post_id=' + post.post_id + '">' + escapeHtml(post.post_title) + '</a>';
                        if (isMyPost) {
                            newRow += ' <button onclick="post_delete(' + post.post_id + ')">삭제</button>';
                        }
                        newRow += '</td>';
                        newRow += '<td>' + escapeHtml(post.nickname) + '</td>';
                        newRow += '<td>' + escapeHtml(post.insert_date) + '</td>';
                        newRow += '</tr>';
                        tbody.append(newRow);
                        offset++;
                    }
                });
            }
        });
    }
    let offset = 10;

    $('#load_more').on('click', function() {
        $.post("main_page.api.php", { offset: offset })
        .done(function(result) {
            if (result.result && result.post_data.length > 0) {
                const tbody = $('table tbody');
                result.post_data.forEach(function(post, index) {
                    const isMyPost = post.member_id == <?= $_SESSION['member_id'] ?>;
                    let row = '<tr>';
                    row += '<td>' + (offset + index + 1) + '</td>';
                    row += '<td>';
                    row += '<a class="post_title" href="view.php?post_id=' + post.post_id + '">' + escapeHtml(post.post_title) + '</a>';
                    if (isMyPost) {
                        row += ' <button onclick="post_delete(' + post.post_id + ')">삭제</button>';
                    }
                    row += '</td>';
                    row += '<td>' + escapeHtml(post.nickname) + '</td>';
                    row += '<td>' + escapeHtml(post.insert_date) + '</td>';
                    row += '</tr>';
                    tbody.append(row);
                });
                offset += result.post_data.length;
            } else {
                alert("더 이상 글이 없습니다.");
                $('#load_more').hide();
            }
        });
    });

    // HTML 이스케이프 함수
    function escapeHtml(str) {
        return $('<div>').text(str).html();
    }
    </script>
</head>
<body>
<?php require_once("header.php"); ?>
<h1>📋 게시판 목록 📋</h1>

<!-- 글 작성 폼 -->
<form method="POST" action="write.post.php">
    <p>
        <input type="text" maxlength="50" name="post_title" placeholder="제목(50자)" style="width:80%; padding:8px;" autocomplete = "off" required>
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
                    <?php if ($post['member_id'] == $_SESSION['member_id']): ?>
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
<button id="load_more" style="margin-top: 20px;">더보기</button>

</body>

</html>