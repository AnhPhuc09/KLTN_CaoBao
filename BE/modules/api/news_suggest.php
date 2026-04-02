<?php
require_once __DIR__ . '/cors.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "crawl_news";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}
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
$query = $conn->real_escape_string($query);
$sql = "SELECT title 
        FROM crawl_news 
        WHERE title LIKE '%$query%' 
        ORDER BY pubDate DESC 
        LIMIT 20";
$result = $conn->query($sql);
$suggestions = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['title'])) {
            $suggestions[] = $row['title'];
        }
    }
}
$conn->close();
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
