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
require_once __DIR__ . '/../../includes/database.php';
global $conn;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0) {
    header("Location: ?module=admin&action=news");
    exit;
}

// LẤY THÔNG TIN BÀI BÁO
$sql = "SELECT * FROM crawl_news WHERE id = $id";
$article = getOne($sql);
if(!$article) {
    // Nếu không tìm thấy trong crawl_news, quay lại (hoặc bảng tên khác)
    header("Location: ?module=admin&action=news");
    exit;
}

$msg = '';
$msgType = '';

// XỬ LÝ CẬP NHẬT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateData = [];
    // Tự động lặp qua các field được post lên (trừ các field không có trong DB)
    foreach($_POST as $key => $val) {
        if(array_key_exists($key, $article) && $key !== 'id') {
            $updateData[$key] = trim($val);
        }
    }
    
    if(!empty($updateData) && !empty($updateData['title'])) {
        try {
            update('crawl_news', $updateData, "id = $id");
            $msg = "Cập nhật bài báo thành công!";
            $msgType = "success";
            // Lấy lại dữ liệu mới nhất
            $article = getOne($sql);
        } catch (Exception $e) {
            $msg = "Có lỗi xảy ra: " . $e->getMessage();
            $msgType = "danger";
        }
    } else {
        $msg = "Vui lòng nhập đầy đủ tiêu đề bài báo.";
        $msgType = "warning";
    }
}

// LẤY DANH SÁCH DANH MỤC (NẾU CÓ BẢNG CATEGORY)
$categories = [];
try {
    $catSql = "SELECT * FROM category";
    $categories = getAll($catSql);
} catch (Exception $e) {
    $categories = [];
}

renderView('admin/news_edit', [
    'article' => $article,
    'categories' => $categories,
    'msg' => $msg,
    'msgType' => $msgType
]);
