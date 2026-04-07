<?php
define('_TAI', true);
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$user_id = $_SESSION['user_id'] ?? 0;
$perPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$where = " WHERE 1=1 ";
$types = "";
$params = [];

if (!empty($keyword)) {
    $where .= " AND title LIKE ? ";
    $types .= "s";
    $params[] = "%" . $keyword . "%";
}

if (!empty($category)) {
    $where .= " AND category = ? ";
    $types .= "s";
    $params[] = $category;
}

$sqlCount = "SELECT COUNT(id) as total FROM crawl_news $where";
$stmtCount = $conn->prepare($sqlCount);
if (!empty($params)) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$totalResult = $stmtCount->get_result()->fetch_assoc();
$total = $totalResult ? $totalResult['total'] : 0;
$stmtCount->close();

$sql = "SELECT n.*,
       EXISTS (
           SELECT 1 
           FROM favourite_news f 
           WHERE f.news_id = n.id AND f.user_id = ?
       ) AS is_favourite
FROM crawl_news n
$where
ORDER BY pubDate DESC
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$typesAll = "i" . $types . "ii";
$paramsAll = [$user_id];
foreach ($params as $p) {
    $paramsAll[] = $p;
}
$paramsAll[] = $perPage;
$paramsAll[] = $offset;

$stmt->bind_param($typesAll, ...$paramsAll);
$stmt->execute();
$result = $stmt->get_result();

$listNews = [];
while ($row = $result->fetch_assoc()) {
    $row['is_favourite'] = (bool) $row['is_favourite'];
    $listNews[] = $row;
}
$stmt->close();
echo json_encode([
    'status' => 'success',
    'data' => $listNews,
    'pagination' => [
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total,
        'totalPages' => ceil($total / $perPage)
    ]
], JSON_UNESCAPED_UNICODE);
