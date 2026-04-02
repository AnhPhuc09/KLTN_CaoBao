<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/database.php';

$news_id = isset($_GET['news_id']) ? (int) $_GET['news_id'] : 0;
if ($news_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID bài báo không hợp lệ']);
    exit;
}
$sql = "
    SELECT c.id, c.user_id, c.news_id, c.content, c.created_at, u.fullname, u.avatar
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.news_id = ?
    ORDER BY c.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();
$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$stmt->close();
echo json_encode([
    'status' => 'success',
    'news_id' => $news_id,
    'data' => $comments
]);
