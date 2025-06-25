<!DOCTYPE html>
<html>
    <head>
        <title>로그인</title>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <h1>로그인</h1>
        <form method="POST" action="login.post.php">
        <p>
            아이디 :
            <input type="text" name="login_id" maxlength="10" placeholder="10자" autocomplete = "off" />
        <p>
        <p>
            비밀번호 :
            <input type="password" name="login_pw" maxlength="20" placeholder="20자" autocomplete = "off" />
        <p>
        <p><input type="submit" value="로그인">&nbsp&nbsp<a href="/php/community/regist.php">회원가입</a></p>
        
        </form>
    </body>
</html>