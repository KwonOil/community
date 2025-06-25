<?php
session_start();
require_once("db.php");

header("Content-Type: text/plain; charset=UTF-8");

// 로그인 확인
if (!isset($_SESSION['member_id'])) {
    echo "로그인이 필요합니다.";
    exit();
}

// 사용자 입력 받기
$input_pw = isset($_POST['pw']) ? $_POST['pw'] : '';
$member_id = (int)$_SESSION['member_id'];

// DB에서 비밀번호 가져오기
$query = "SELECT login_pw FROM tbl_member WHERE member_id = ?";
$data = db_select($query, [$member_id]);

if ($data && count($data) > 0) {
    $db_pw = $data[0]['login_pw'];

    // 비밀번호 비교
    if (password_verify($input_pw, $db_pw)) {
        echo "비밀번호 일치";
    } else {
        echo "비밀번호 불일치";
    }
} else {
    echo "회원 정보를 찾을 수 없습니다.";
}
