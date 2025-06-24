<!DOCTYPE html>
<html>
    <head>
        <title>php-board 로그인</title>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <h1>php-board 로그인</h1>
        <form method="POST" action="login.post.php">
        <p>
            아이디 :
            <input type="text" name="login_id" autocomplete = "off" />
        <p>
        <p>
            비밀번호 :
            <input type="password" name="login_pw" autocomplete = "off" />
        <p>
        <p><input type="submit" value="로그인"></p>
        </form>
    </body>
</html>