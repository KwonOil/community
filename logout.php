<?php
session_start();             // 세션 시작
session_unset();            // 모든 세션 변수 제거
session_destroy();          // 세션 자체 제거

// 로그인 페이지로 리다이렉트
header("Location: /php/community/login.php");
exit();