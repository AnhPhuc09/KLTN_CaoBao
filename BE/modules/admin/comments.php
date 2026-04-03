<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ?module=admin&action=loginqtv");
    exit;
}

require_once __DIR__ . '/../../includes/session.php';
global $conn;
if (!isset($conn)) $conn = new mysqli("localhost", "root", "", "crawl_news");

$sql = "SELECT c.*, u.fullname, n.title as news_title 
        FROM comments c 
        LEFT JOIN users u ON c.user_id = u.id 
        LEFT JOIN crawl_news n ON c.news_id = n.id 
        ORDER BY c.created_at DESC LIMIT 50";
$comments = [];

try {
    if (function_exists('getAll')) {
        $comments = getAll($sql);
    }
    if (empty($comments)) {
        $result = $conn->query($sql);
        if($result) {
            while($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
        }
    }
} catch(Exception $e) {
    try {
        $sql2 = "SELECT * FROM comments ORDER BY id DESC LIMIT 50";
        if (function_exists('getAll')) {
            $comments = getAll($sql2);
        }
        if (empty($comments)) {
            $result2 = $conn->query($sql2);
            if($result2) {
                while($row = $result2->fetch_assoc()) {
                    $comments[] = $row;
                }
            }
        }
    } catch(Exception $e2) {
        $comments = [];
    }
}

if(isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM comments WHERE id = $del_id");
    header("Location: ?module=admin&action=comments");
    exit;
}

renderView('admin/comments', [
    'comments' => $comments
]);
