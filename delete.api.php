<?php
header('Content-Type: application/json');
 
// 로그인 체크
session_start();
if (isset($_SESSION['member_id']) === false){
    echo json_encode(array('result' => false));
    exit();
}
 
// 파라미터 체크
$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : null;
if ($post_id == null){
    echo json_encode(array('result' => false));
    exit();
}
 
// DB Require
require_once("db.php");
 
$member_id = $_SESSION['member_id'];
 

// 우선 해당 post_id 글이 존재하는지 확인
$post_exists = db_select("SELECT member_id FROM tbl_post WHERE post_id = ?", [$post_id]);
if (!$post_exists) {
    // 글 자체가 없음
    echo json_encode(['result' => false, 'msg' => '삭제 실패: 글이 존재하지 않습니다.']);
    exit();
}

// 작성자와 로그인 회원이 일치하는지 체크
if ($post_exists[0]['member_id'] != $member_id) {
    echo json_encode(['result' => false, 'msg' => '삭제 실패: 권한이 없습니다.']);
    exit();
}
// 작성자 맞으면 삭제 실행
$result = db_update_delete("DELETE FROM tbl_post WHERE post_id = ? AND member_id = ?", [$post_id, $member_id]);
if ($result) {
    echo json_encode(['result' => true, 'msg' => '삭제 완료되었습니다.']);
} else {
    echo json_encode(['result' => false, 'msg' => '삭제 실패: 알 수 없는 오류']);
}
// // 글 삭제. 작성자 체크를 위해 writer_id 도 함께 검사.
// $result = db_update_delete("delete from tbl_post where post_id = :post_id and member_id = :member_id",
//     array(
//         'post_id' => $post_id,
//         'member_id' => $member_id
//     )
// );
// if ($result) {
//     echo json_encode(['result' => true, 'msg' => '삭제 완료되었습니다.']);
// } else {
//     echo json_encode(['result' => false, 'msg' => '삭제 실패: 권한 없음 또는 글이 존재하지 않음']);
// }