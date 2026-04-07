<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/database.php';
$conn->set_charset("utf8mb4");

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
if (empty($query) && isset($_POST['query'])) {
    $query = trim($_POST['query']);
}
if (empty($query)) {
    $rawData = json_decode(file_get_contents('php://input'), true);
    if (!empty($rawData['query'])) {
        $query = trim($rawData['query']);
    }
}
if (mb_strlen($query) < 2) {
    echo json_encode(["status" => "success", "suggestions" => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $conn->prepare("SELECT title FROM crawl_news WHERE title LIKE ? ORDER BY pubDate DESC LIMIT 20");
$likeQuery = "%" . $query . "%";
$stmt->bind_param("s", $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['title'])) {
            $suggestions[] = $row['title'];
        }
    }
}
$stmt->close();

if (empty($suggestions)) {
    echo json_encode(
        ["status" => "success", "suggestions" => []],
        JSON_UNESCAPED_UNICODE
    );
} else {
    echo json_encode(
        ["status" => "success", "suggestions" => $suggestions],
        JSON_UNESCAPED_UNICODE
    );
}
