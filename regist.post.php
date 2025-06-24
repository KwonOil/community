<?php
require_once("db.php");

$login_id = isset($_POST['login_id']) ? $_POST['login_id'] : null;
$login_pw = isset($_POST['login_pw']) ? $_POST['login_pw'] : null;
$nickname = isset($_POST['nickname']) ? $_POST['nickname'] : null;
// 파라미터 체크
if ($login_id == null || $login_pw == null){
    echo "<script>
        alert('아이디를 입력해주세요');
        history.back();
    </script>";
    exit();
}

// 회원 가입이 되어 있는지 검사
$member_count = db_select("select count(member_id) cnt from tbl_member where login_id = ?" , array($login_id));
if ($member_count && $member_count[0]['cnt'] == 1){
    echo "<script>
        alert('이미 가입된 아이디입니다.');
        history.back();
    </script>";
    exit();
}

// 비밀번호 암호화
$bcrypt_pw = password_hash($login_pw, PASSWORD_BCRYPT);

// 닉네임을 입력하지 않으면 익명으로 저장
if($nickname == null){
    $nickname = "익명";
}
// 데이터 저장
db_insert("insert into tbl_member (login_id, login_pw, nickname) values (:login_id, :login_pw, :nickname)",
    array(
        'login_id' => $login_id,
        'login_pw' => $bcrypt_pw,
        'nickname' => $nickname
    )
);
// 알람 출력 후 로그인 페이지로 이동
echo "<script>
alert('회원가입 되었습니다');
location.href = '/php/community/login.php';
</script>";
exit();