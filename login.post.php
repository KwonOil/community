<?php
require_once("db.php");
 
$login_id = isset($_POST['login_id']) ? $_POST['login_id'] : null;
$login_pw = isset($_POST['login_pw']) ? $_POST['login_pw'] : null;
 
// 파라미터 체크
if ($login_id == null || $login_pw == null){
    header("Location: /php/community/login.php");
    exit();
}
 
// 회원 데이터
$member_data = db_select("select * from tbl_member where login_id = ?", array($login_id));
 
// 회원 데이터가 없다면
if ($member_data == null || count($member_data) == 0){
    echo "<script>
        alert('아이디가 없습니다.');
        history.back();
    </script>";
    exit();
}
 
// 비밀번호 일치 여부 검증
$is_match_password = password_verify($login_pw, $member_data[0]['login_pw']);
 
// 비밀번호 불일치
if ($is_match_password === false){
    echo "<script>
        alert('비밀번호가 틀렸습니다.');
        history.back();
    </script>";
    exit();
}
 
session_start();
$_SESSION['member_id'] = $member_data[0]['member_id'];
 
// 목록으로 이동
header("Location: /php/community/main_page.php");