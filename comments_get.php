<?php
session_start();
require_once("db.php");

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
if (!$post_id) exit("댓글을 불러올 수 없습니다.");

$query = "SELECT c.comment_id, c.content, c.insert_date, c.member_id, m.nickname
        FROM tbl_comment c
        JOIN tbl_member m ON c.member_id = m.member_id
        WHERE c.post_id = ? ORDER BY c.insert_date DESC";

$login_member_id = $_SESSION['member_id'] ?? 0;

$comments = db_select($query, [$post_id]);

foreach ($comments as $c) {
    echo "<div style='border-bottom:1px solid #ccc; padding:10px 0;'>";
    echo "<strong>" . htmlspecialchars($c['nickname']) . "</strong><br>";
    echo "<span>" . nl2br(htmlspecialchars($c['content'])) . "</span><br>";
    echo "<small style='color:gray;'>" . $c['insert_date'] . "</small>";
    if ($login_member_id == $c['member_id'] || $login_member_id === 1) {
        echo ' <button class="delete-comment-btn" data-comment-id="' . $c['comment_id'] . '">삭제</button>';
    }
    echo "</div>";
}
