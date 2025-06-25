<!DOCTYPE html>
<html>
    <head>
        <title>회원가입</title>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <h1>회원가입</h1>
        <form method="POST" action="regist.post.php">
        <p>
            *아이디 :
            <input type="text" name="login_id" maxlength="10" placeholder="10자" required />
        </p>
        <p>
            *비밀번호 :
            <input type="password" name="login_pw" maxlength="20" placeholder="20자" required />
        </p>
        <p>
            닉네임 :
            <input type="text" name="nickname" maxlength="10" placeholder="10자" autocomplete = "off" />
        </p>
        <p>
            이메일 :
            <input type="email" name="email" maxlength="20" placeholder="20자" autocomplete = "off" />
        </p>
        <input type="submit" value="등록">
        <a href="/php/community/login.php">로그인</a>
        </form>
        <p>
            (*는 필수입니다)
        </p>
    </body>
</html>