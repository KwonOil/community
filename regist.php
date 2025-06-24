<!DOCTYPE html>
<html>
    <head>
        <title>php-board 회원가입</title>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <h1>php-board 회원가입</h1>
        <form method="POST" action="regist.post.php">
        <p>
            *아이디 :
            <input type="text" name="login_id" autocomplete = "off" required />
        <p>
        <p>
            *비밀번호 :
            <input type="password" name="login_pw" required />
        <p>
        <p>
            닉네임 :
            <input type="text" name="nickname" autocomplete = "off" />
        <p>
        <p><input type="submit" value="회원가입"></p>
        </form>
        <p>
            (*는 필수입니다)
        <p>
    </body>
</html>