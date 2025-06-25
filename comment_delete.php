<?php
session_start();
require_once("db.php");

header('Content-Type: application/json');

if (!isset($_SESSION['member_id'])) {
    echo json_encode(['result' => false, 'msg' => '로그인이 필요합니다.']);
    exit();
}

$member_id = $_SESSION['member_id'];
$comment_id = $_POST['comment_id'] ?? null;

if (!$comment_id || !is_numeric($comment_id)) {
    echo json_encode(['result' => false, 'msg' => '잘못된 요청입니다.']);
    exit();
}

// 본인 댓글인지 확인
$query = "SELECT member_id FROM tbl_comment WHERE comment_id = ?";
$comment = db_select($query, [$comment_id]);
if (!($comment && ($comment[0]['member_id'] == $member_id || $member_id === 1))) {
    echo json_encode(['result' => false, 'msg' => '권한이 없습니다.']);
    exit();
}

// 댓글 삭제
$delete_query = "DELETE FROM tbl_comment WHERE comment_id = ?";
$success = db_update_delete($delete_query, [$comment_id]);

if ($success) {
    echo json_encode(['result' => true, 'msg' => '댓글을 삭제했습니다']);
} else {
    echo json_encode(['result' => false, 'msg' => '삭제 실패']);
}