<?php
session_start();

// 로그인한 사람만 접근 가능
if (!isset($_SESSION['member_id'])) {
    echo "로그인이 필요합니다.";
    exit();
}

// DB 작업
require_once("db.php");
$member_id = (int)$_SESSION['member_id'];

$query = "SELECT * FROM tbl_member WHERE member_id = ?";
$myinfo_data = db_select($query, [$member_id]);

if ($myinfo_data && count($myinfo_data) > 0) {
    $myinfo = $myinfo_data[0];
} else {
    echo "회원 정보를 찾을 수 없습니다.";
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <title>내 정보</title>
    <style>
        .upw input[type="password"],
        .upw button {
            height: 25px;
            font-size: 15px;
            padding: 0 5px;
            box-sizing: border-box;
            vertical-align: middle;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#pwForm").submit(function (e) {
                e.preventDefault();

                const inputPw = $("#inputPw").val();

                $.ajax({
                    url: "check_password.php",
                    type: "POST",
                    data: { pw: inputPw },
                    success: function (response) {
                        alert(response); // 예: "비밀번호 일치" 또는 "비밀번호 불일치"
                    },
                    error: function () {
                        alert("서버 오류가 발생했습니다.");
                    }
                });
            });
        });
    </script>
</head>
<body>
    <?php require_once("header.php"); ?>
    <div class = "userinfo">
        <!-- 아이디 -->
        <p class="uid">아이디 : <?= htmlspecialchars($myinfo['login_id']) ?></p>
        <!-- 비밀번호 확인 -->
        <form class="upw" id="pwForm">
            비밀번호 확인<br><input type="password" id="inputPw" />
            <button type="submit">확인</button>
        </form>
        <button type="submit">비밀번호 수정</button>
        <!-- 닉네임 -->
        <p class="unickname">닉네임 : <?= htmlspecialchars($myinfo['nickname']) ?></p>
        <!-- 이메일 -->
        <p class="uemail">이메일 : <?= htmlspecialchars($myinfo['email']) ?></p>
        <!-- 가입날짜 -->
        <p class="ujoindate">가입일 : <?= htmlspecialchars($myinfo['join_date']) ?></p>
    </div>
    <button onclick="history.back()">뒤로가기</button>
</body>
</html>
