<?php
require_once __DIR__ . '/cors.php';

$apiKey = "AIzaSyD-U2KV7v_iFzZ8DUj61U-yCCPEURxX7MU";
$caCertPath = "D:\\laragon\\etc\\ssl\\cacert.pem";
$model = "gemini-2.5-flash";
$mysqli = new mysqli("localhost", "root", "", "crawl_news");
$mysqli->set_charset("utf8mb4");
if ($mysqli->connect_error) {
    echo json_encode(["status" => "error", "message" => "Lỗi kết nối CSDL"]);
    exit;
}
$articleId = max(0, intval($_GET['id'] ?? 0));
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = max(1, intval($_GET['perPage'] ?? 5));
if ($articleId <= 0) {
    echo json_encode(["status" => "error", "message" => "Thiếu hoặc sai ID bài báo"]);
    exit;
}
$current = $mysqli->query("SELECT id, title, category, source, pubDate FROM crawl_news WHERE id=$articleId LIMIT 1")->fetch_assoc();
if (!$current) {
    echo json_encode(["status" => "error", "message" => "Không tìm thấy bài báo"]);
    exit;
}
$fallback = [];
$offset = ($page - 1) * $perPage;
$stmt = $mysqli->prepare("
    SELECT id, title, link, image, source, pubDate 
    FROM crawl_news
    WHERE id <> ? AND category = ? 
    ORDER BY pubDate DESC 
    LIMIT ?, ?
");
$stmt->bind_param("isii", $articleId, $current['category'], $offset, $perPage);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $fallback[] = $row;
}
$stmt->close();
$totalRes = $mysqli->prepare("
    SELECT COUNT(*) as cnt 
    FROM crawl_news 
    WHERE id <> ? AND category = ?
");
$totalRes->bind_param("is", $articleId, $current['category']);
$totalRes->execute();
$total = $totalRes->get_result()->fetch_assoc()['cnt'];
$totalRes->close();

$cache = $mysqli->prepare("SELECT related, updated_at FROM ai_related WHERE article_id=? AND updated_at > NOW() - INTERVAL 12 HOUR");
$cache->bind_param("i", $articleId);
$cache->execute();
$cacheRes = $cache->get_result()->fetch_assoc();
$cache->close();
if ($cacheRes) {
    echo json_encode([
        "status" => "success",
        "related" => json_decode($cacheRes['related'], true),
        "page" => $page,
        "perPage" => $perPage,
        "total" => $total,
        "aiPending" => false
    ], JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}
echo json_encode([
    "status" => "success",
    "related" => $fallback,
    "page" => $page,
    "perPage" => $perPage,
    "total" => $total,
    "aiPending" => true
], JSON_UNESCAPED_UNICODE);
register_shutdown_function(function () use ($current, $articleId, $apiKey, $model, $caCertPath) {
    $mysqli = new mysqli("localhost", "root", "", "crawl_news");
    $mysqli->set_charset("utf8mb4");
    $others = [];
    $res = $mysqli->query("
        SELECT id, title, link, image, source, pubDate, category
        FROM crawl_news 
        WHERE id<>$articleId 
        ORDER BY pubDate DESC 
        LIMIT 20
    ");
    while ($row = $res->fetch_assoc()) {
        $others[] = $row;
    }
    $prompt = "Bài báo hiện tại: " . json_encode($current, JSON_UNESCAPED_UNICODE) .
        ". Danh sách các bài khác: " . json_encode($others, JSON_UNESCAPED_UNICODE) .
        ". Hãy chọn tối đa 10 bài LIÊN QUAN NHẤT về chủ đề/ngữ cảnh.
        Trả về JSON thuần dạng: [{\"id\":123}, {\"id\":456}, ...] 
        KHÔNG thêm text ngoài JSON.";
    $data = ["contents" => [["role" => "user", "parts" => [["text" => $prompt]]]]];
    $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_CAINFO => $caCertPath
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    $apiRes = json_decode($res, true);
    $ids = [];
    if (!empty($apiRes['candidates'][0]['content']['parts'][0]['text'])) {
        $text = trim($apiRes['candidates'][0]['content']['parts'][0]['text']);
        $text = preg_replace('/^```json/i', '', $text);
        $text = preg_replace('/```$/', '', $text);
        $json = json_decode($text, true);
        if (is_array($json)) {
            foreach ($json as $item) {
                if (!empty($item['id'])) {
                    $ids[] = intval($item['id']);
                }
            }
        }
    }
    if ($ids) {
        $idList = implode(",", array_map('intval', array_unique($ids)));
        $res = $mysqli->query("SELECT id, title, link, image, source, pubDate FROM crawl_news WHERE id IN ($idList) ORDER BY FIELD(id,$idList)");
        $aiRelated = [];
        while ($row = $res->fetch_assoc()) {
            $aiRelated[] = $row;
        }
        $stmt = $mysqli->prepare("REPLACE INTO ai_related (article_id, related) VALUES (?, ?)");
        $json = json_encode($aiRelated, JSON_UNESCAPED_UNICODE);
        $stmt->bind_param("is", $articleId, $json);
        $stmt->execute();
        $stmt->close();
    }
    $mysqli->close();
});
