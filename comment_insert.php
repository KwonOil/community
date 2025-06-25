<?php
session_start();
require_once("db.php");
header("Content-Type: application/json; charset=UTF-8");

$member_id = $_SESSION['member_id'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$content = trim($_POST['content'] ?? '');

if (!$post_id || $content === '') {
    echo json_encode(['result' => false, 'msg' => '입력값 오류']);
    exit();
}

$query = "INSERT INTO tbl_comment (post_id, member_id, content) VALUES (?, ?, ?)";
$result = db_insert($query, [$post_id, $member_id, $content]);


echo json_encode(['result' => $result, 'msg' => $result ? '댓글 등록 완료' : '등록 실패']);
