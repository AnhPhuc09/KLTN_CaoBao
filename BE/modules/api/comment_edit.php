<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/database.php';

$response = ['status' => 'error', 'message' => 'Lỗi không xác định.'];
if (empty($_SESSION['user_id'])) {
    $response['message'] = 'Bạn cần đăng nhập để thực hiện hành động này.';
    echo json_encode($response);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Yêu cầu không hợp lệ. Vui lòng gửi POST method.';
    echo json_encode($response);
    exit;
}
$inputData = json_decode(file_get_contents('php://input'), true);
$comment_id = isset($_POST['comment_id']) ? (int) $_POST['comment_id'] : (int)($inputData['comment_id'] ?? 0);
$content = trim($_POST['content'] ?? ($inputData['content'] ?? ''));
if ($comment_id <= 0) {
    $response['message'] = 'ID bình luận không hợp lệ.';
    echo json_encode($response);
    exit;
}
if ($content === '') {
    $response['message'] = 'Nội dung bình luận không được để trống.';
    echo json_encode($response);
    exit;
}
$stmt = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ($result) {
    $commentOwner = $result['user_id'];
    $currentUser  = (int)$_SESSION['user_id'];
    $isAdmin      = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    if ($commentOwner == $currentUser || $isAdmin) {
        $stmt = $conn->prepare("UPDATE comments SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $comment_id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Cập nhật thành công!';
            $response['content'] = htmlspecialchars($content);
        } else {
            $response['message'] = 'Lỗi khi cập nhật cơ sở dữ liệu.';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Bạn không có quyền sửa bình luận này.';
    }
} else {
    $response['message'] = 'Không tìm thấy bình luận.';
}
echo json_encode($response);
exit;
