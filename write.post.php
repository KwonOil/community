<?php
// 로그인 체크
session_start();
if (isset($_SESSION['member_id']) === false){
    header("Location: /php/community/main_page.php");
    exit();
}
 
// 제목이 있는지 파라미터 체크
$post_title = isset($_POST['post_title']) ? $_POST['post_title'] : null;
if ($post_title == null || trim($post_title) == ''){
    header("Location: /php/community/main_page.php");
    exit();
}

// 글이 있는지 파라미터 체크
$post_content = isset($_POST['post_content']) ? $_POST['post_content'] : null;
if ($post_content == null || trim($post_content) == ''){
    header("Location: /php/community/main_page.php");
    exit();
}
 
// DB Require
require_once("db.php");
 
$member_id = $_SESSION['member_id'];
// tbl_post 입력
$post_id = db_insert("insert into tbl_post (post_title, post_content, member_id) values (:post_title, :post_content, :member_id)",
    array(
        'post_title'=> $post_title,
        'post_content'=> $post_content,
        'member_id'=> $member_id
    )
);

header("Location: /php/community/main_page.php");
exit();