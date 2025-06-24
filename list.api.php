<?php
header('Content-Type: application/json'); // 응답을 JSON 형식으로 지정

session_start();

// 로그인 여부 확인
if (!isset($_SESSION['member_id'])) {
    echo json_encode(['result' => false, 'msg' => '로그인이 필요합니다.']);
    exit();
}

require_once("db.php"); // DB 연결 함수 포함

// 클라이언트에서 전달된 offset과 limit 값 처리
$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;  // 시작 위치 (기본값: 0)
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;    // 가져올 게시글 수 (기본값: 10)

try {
    $pdo = db_get_pdo(); // PDO 객체 생성

    // 게시글 목록을 최신순으로 가져오는 SQL
    $query = "
        SELECT p.post_id, p.post_title, p.post_content, p.member_id, p.insert_date, m.nickname
        FROM tbl_post p
        JOIN tbl_member m ON p.member_id = m.member_id
        ORDER BY p.insert_date DESC
        LIMIT :offset, :limit
    ";

    $stmt = $pdo->prepare($query);

    // LIMIT 절은 반드시 정수형 바인딩이 필요함
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    $post_data = $stmt->fetchAll(PDO::FETCH_ASSOC); // 결과를 연관배열로 가져옴

    // 게시글이 없을 경우
    if (empty($post_data)) {
        echo json_encode(['result' => false, 'msg' => '더 이상 글이 없습니다.']);
    } else {
        echo json_encode(['result' => true, 'post_data' => $post_data]);
    }
} catch (PDOException $e) {
    // DB 오류 처리
    echo json_encode(['result' => false, 'msg' => 'DB 오류']);
}
