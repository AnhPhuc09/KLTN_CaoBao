<?php
require_once __DIR__ . '/cors.php';
define('_TAI', true);
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$user_id = $_SESSION['user_id'] ?? 0;
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$keyword  = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$where = " WHERE 1=1 ";
if (!empty($keyword)) {
    $keyword = addslashes($keyword);
    $where .= " AND title LIKE '%$keyword%' ";
}

if (!empty($category)) {
    $category = addslashes($category);
    $where .= " AND category = '$category' ";
}
$sqlCount = "SELECT COUNT(id) as total FROM crawl_news $where";
$totalResult = getOne($sqlCount);
$total = $totalResult ? $totalResult['total'] : 0;
$sql = "SELECT n.*,
       EXISTS (
           SELECT 1 
           FROM favourite_news f 
           WHERE f.news_id = n.id AND f.user_id = $user_id
       ) AS is_favourite
FROM crawl_news n
$where
ORDER BY pubDate DESC
LIMIT $perPage OFFSET $offset";
$listNews = getAll($sql);
if ($listNews) {
    foreach ($listNews as &$item) {
        $item['is_favourite'] = (bool)$item['is_favourite'];
    }
    unset($item);
} else {
    $listNews = [];
}
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
