<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: /");
    exit();
}

require_once("db.php");

$member_id = $_SESSION['member_id'];

// GET: 수정폼 보여주기
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
        echo "잘못된 접근입니다.";
        exit();
    }
    $post_id = (int)$_GET['post_id'];

    $query = "SELECT post_id, post_title, post_content FROM tbl_post WHERE post_id = ? AND member_id = ?";
    $post_data = db_select($query, [$post_id, $member_id]);

    if (!$post_data || count($post_data) === 0) {
        echo "<script>alert('존재하지 않는 글이거나 권한이 없습니다.'); history.back();</script>";
        exit();
    }

    $post = $post_data[0];
    ?>

    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8" />
        <title>글 수정하기</title>
        <style>
            body { max-width: 700px; margin: 30px auto; font-family: Arial, sans-serif; }
            textarea, input[type=text] { width: 100%; padding: 10px; margin-bottom: 15px; font-size: 16px; }
            input[type=submit] { padding: 10px 20px; font-size: 16px; }
        </style>
    </head>
    <body>
        <h1>글 수정하기</h1>
        <form method="POST" action="edit.post.php">
            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
            <input type="text" name="post_title" required value="<?= htmlspecialchars($post['post_title']) ?>" />
            <textarea name="post_content" rows="10" required><?= htmlspecialchars($post['post_content']) ?></textarea>
            <input type="submit" value="수정 완료" />
        </form>
        <a href="view.php?post_id=<?= $post['post_id'] ?>">취소</a>
    </body>
    </html>

    <?php
    exit();
}

// POST: 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['post_id'], $_POST['post_title'], $_POST['post_content'])
        || !is_numeric($_POST['post_id'])
    ) {
        echo "잘못된 접근입니다.";
        exit();
    }

    $post_id = (int)$_POST['post_id'];
    $title = trim($_POST['post_title']);
    $content = trim($_POST['post_content']);

    if ($title === '' || $content === '') {
        echo "제목과 내용을 모두 입력하세요.";
        exit();
    }

    $query = "UPDATE tbl_post SET post_title = ?, post_content = ? WHERE post_id = ? AND member_id = ?";
    $result = db_update_delete($query, [$title, $content, $post_id, $member_id]);

    if ($result) {
        header("Location: view.php?post_id=" . $post_id);
        exit();
    } else {
        echo "수정에 실패했습니다.";
    }
}
