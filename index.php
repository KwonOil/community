<?php
// 로그인 되어 있으면 목록으로 이동
session_start();
if (isset($_SESSION['member_id'])){
    header("Location: /php/community/main_page.php");
    exit();
}
?>

<!-- 서비스 소개 -->
<!DOCTYPE html>
<html>
    <head>
        <title>php-board 메인</title>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <h1>php-board 첫 페이지</h1>
    </body>
</html>