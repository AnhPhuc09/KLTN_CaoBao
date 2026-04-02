<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/database.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để bình luận']);
    exit;
}

$inputData = json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_SESSION['user_id'];
    $news_id = (int)($_POST['news_id'] ?? ($inputData['news_id'] ?? 0));
    $content = trim($_POST['content'] ?? ($inputData['content'] ?? ''));
    if ($news_id > 0 && $content !== '') {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, news_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $user_id, $news_id, $content);
        if ($stmt->execute()) {
            $insert_id = $stmt->insert_id;
            $stmt->close();
            $sql = "SELECT c.id, c.user_id, c.news_id, c.content, c.created_at, u.fullname, u.avatar
                    FROM comments c JOIN users u ON c.user_id = u.id WHERE c.id = ?";
            $st2 = $conn->prepare($sql);
            $st2->bind_param("i", $insert_id);
            $st2->execute();
            $newComment = $st2->get_result()->fetch_assoc();
            $st2->close();
            echo json_encode([
                'status' => 'success',
                'message' => 'Thêm bình luận thành công',
                'data' => $newComment
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra, không thể thêm bình luận']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nội dung hoặc ID bài báo không hợp lệ']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Chỉ hỗ trợ POST request']);
}
