<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);

require_once __DIR__ . '/cors.php';
define('_TAI', true);
require_once dirname(__DIR__, 2) . "/includes/connect.php";

$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Bạn cần đăng nhập để thực hiện thao tác này'
    ]);
    exit;
}
$user_id = intval($_SESSION['user_id']);
$news_id = intval($_POST['news_id'] ?? ($inputData['news_id'] ?? 0));
if ($news_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => "news_id không hợp lệ"
    ]);
    exit;
}
try {
    $stmtDel = $conn->prepare("DELETE FROM favourite_news WHERE user_id = ? AND news_id = ?");
    $stmtDel->execute([$user_id, $news_id]);
    echo json_encode([
        'status' => 'success',
        'action' => 'removed',
        'message' => 'Đã xóa khỏi yêu thích'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
