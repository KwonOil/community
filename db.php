<?php
function db_get_pdo()
{
    $host = 'localhost';
    $port = '3306';
    $dbname = 'phpboard';
    $charset = 'utf8mb4';
    $username = 'boarduser';
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
