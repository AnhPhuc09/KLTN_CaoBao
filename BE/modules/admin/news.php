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

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 100; 
$offset = ($page - 1) * $limit;

// Count
$totalRecords = 0;
$countSql = "SELECT COUNT(id) as total FROM crawl_news";
$countResult = $conn->query($countSql);
if ($countResult && $row = $countResult->fetch_assoc()) {
    $totalRecords = $row['total'];
}
$totalPages = ceil($totalRecords / $limit);

// Lấy danh sách, uu tiên mới nhất dựa trên pubdate
$sql = "SELECT * FROM crawl_news ORDER BY pubdate DESC, id DESC LIMIT $limit OFFSET $offset";
$news = [];
$result = $conn->query($sql);
if ($result) {
    while($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}

// XÓA NEWS 
if(isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM crawl_news WHERE id = $del_id");
    header("Location: ?module=admin&action=news");
    exit;
}

renderView('admin/news', [
    'news' => $news,
    'page' => $page,
    'totalPages' => $totalPages,
    'totalRecords' => $totalRecords,
    'limit' => $limit
]);
