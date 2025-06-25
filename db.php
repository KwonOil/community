<?php
function db_get_pdo()
{
    // 아래 SQL문을 붙여넣기
    // db 이름 : ptest, 유저 이름 : ptest, 비밀번호 4321 <- 사용할 때 꼭 수정할 것
    // // DB 생성
    // CREATE DATABASE `ptest` /*!40100 COLLATE 'utf8mb4_general_ci' */;
    // // 유저 생성
    // CREATE USER 'ptest'@'%' IDENTIFIED BY '4321';
    // // 권한 부여
    // GRANT EXECUTE, SELECT, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE, CREATE ROUTINE, CREATE TEMPORARY TABLES, CREATE VIEW, DELETE, DROP, EVENT, INDEX, INSERT, REFERENCES, TRIGGER, UPDATE, LOCK TABLES  ON `ptest`.* TO 'ptest'@'%' WITH GRANT OPTION;
    // FLUSH PRIVILEGES;
    // // 글 목록 테이블 생성
    // CREATE TABLE tbl_post (
    //     post_id INT AUTO_INCREMENT PRIMARY KEY,
    //     member_id INT NOT NULL,
    //     post_title VARCHAR(255) NOT NULL,
    //     post_content TEXT NOT NULL,
    //     insert_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    //     update_date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    // );
    // // 멤버 테이블 생성
    // CREATE TABLE tbl_member (
    //     member_id INT AUTO_INCREMENT PRIMARY KEY,
    //     login_id VARCHAR(100) NOT NULL UNIQUE,
    //     login_pw VARCHAR(255) NOT NULL,
    //     nickname VARCHAR(100),
    //     email VARCHAR(200),
    //     join_date DATETIME DEFAULT CURRENT_TIMESTAMP
    // );
    // // 댓글 테이블 생성
    // CREATE TABLE tbl_comment (
    //     comment_id INT AUTO_INCREMENT PRIMARY KEY,
    //     post_id INT NOT NULL,
    //     member_id INT NOT NULL,
    //     content TEXT NOT NULL,
    //     insert_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    //     FOREIGN KEY (post_id) REFERENCES tbl_post(post_id),
    //     FOREIGN KEY (member_id) REFERENCES tbl_member(member_id)
    //     );

    $host = 'localhost';
    $port = '3306';
    $dbname = 'phpboard';
    $charset = 'utf8mb4';
    $username = 'phpboard';
    $db_pw = "5157";

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

    try {
        $pdo = new PDO($dsn, $username, $db_pw);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 예외 발생
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // 기본 fetch 형식
        return $pdo;
    } catch (PDOException $e) {
        error_log("DB 연결 실패: " . $e->getMessage());
        return null;
    }
}

function db_select($query, $param = array())
{
    $pdo = db_get_pdo();
    if (!$pdo) return false;

    try {
        $st = $pdo->prepare($query);
        $st->execute($param);
        return $st->fetchAll();
    } catch (PDOException $ex) {
        error_log("SELECT 오류: " . $ex->getMessage());
        return false;
    }
}

function db_insert($query, $param = array())
{
    $pdo = db_get_pdo();
    if (!$pdo) return false;

    try {
        $st = $pdo->prepare($query);
        $st->execute($param);
        return $pdo->lastInsertId();
    } catch (PDOException $ex) {
        error_log("INSERT 오류: " . $ex->getMessage());
        return false;
    }
}

function db_update_delete($query, $param = array())
{
    $pdo = db_get_pdo();
    if (!$pdo) return false;

    try {
        $st = $pdo->prepare($query);
        return $st->execute($param);
    } catch (PDOException $ex) {
        error_log("UPDATE/DELETE 오류: " . $ex->getMessage());
        return false;
    }
}