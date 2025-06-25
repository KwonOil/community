<p style='text-align:right'>
    <?php
    // PHP_SESSION_NONE: 세션이 시작되지 않음
    // PHP_SESSION_ACTIVE: 세션이 시작됨
    // PHP_SESSION_DISABLED: 세션 기능이 꺼져 있음
    // 세션이 시작되지 않았다면
    if (session_status() === PHP_SESSION_NONE){session_start();}
    
    // 로그인이 되어있지 않았다면
    if (isset($_SESSION['member_id']) === false){
    ?>
    <a href="/php/community/regist.php">회원가입</a>
    <a href="/php/community/login.php">로그인</a>
    
    <?php
    // 로그인이 되어 있다면
    }else{
        $query = "SELECT nickname FROM tbl_member WHERE member_id = ?";
        $result = db_select($query, [$member_id]);

        $nickname = $result[0]['nickname'];
        echo htmlspecialchars($nickname);
    ?>
    <a href="/php/community/myinfo.php">내 정보</a>
    <a href="/php/community/logout.php">로그아웃</a>
    <?php
    }
    ?>
</p>