<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/database.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thao tác.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ. Vui lòng gửi POST.']);
    exit;
}
$inputData = json_decode(file_get_contents('php://input'), true);
$comment_id = isset($_POST['comment_id']) ? (int) $_POST['comment_id'] : (int)($inputData['comment_id'] ?? 0);
if ($comment_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi ID bình luận.']);
    exit;
}
$stmt = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ($result) {
    $commentOwner = $result['user_id'];
    $currentUser  = $_SESSION['user_id'];
    $isAdmin      = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    if ($commentOwner == $currentUser || $isAdmin) {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $comment_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Đã xóa bình luận thành công.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể xóa comment khỏi hệ thống.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Bạn không được phép xóa bình luận của người khác.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy bình luận.']);
}
