<?php
require_once __DIR__ . '/cors.php';

$apiKey = "AIzaSyBcGkqMqWqa0Kdn76FXTDVCRsC3tce-aJo";
$caCertPath = "D:\\laragon\\etc\\ssl\\cacert.pem";
$model = "gemini-2.5-flash";
function callGeminiApi(array $data, string $apiKey, string $model, string $caCertPath): ?array
{
    $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json; charset=utf-8"],
        CURLOPT_CAINFO => $caCertPath,
        CURLOPT_TIMEOUT => 30,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        $err = curl_error($ch);
        curl_close($ch);
        return ["error" => "cURL error: " . $err];
    }
    curl_close($ch);

    $responseData = json_decode($response, true);
    if (!$responseData) {
        return ["error" => "Không thể decode JSON", "http_code" => $httpCode, "raw" => $response];
    }
    if ($httpCode !== 200) {
        return ["error" => "HTTP $httpCode: " . ($responseData['error']['message'] ?? 'Unknown error')];
    }
    if (isset($responseData['error'])) {
        return ["error" => "API error: " . $responseData['error']['message'], "http_code" => $httpCode];
    }
    return $responseData;
}

function callGeminiApiWithRetry(array $data, string $apiKey, string $model, string $caCertPath, int $retries = 2, int $delaySeconds = 2): array
{
    for ($i = 0; $i <= $retries; $i++) {
        $resp = callGeminiApi($data, $apiKey, $model, $caCertPath);
        if (!isset($resp['error']) || !preg_match('/overloaded|quota|temporarily unavailable/i', $resp['error'])) {
            return $resp;
        }
        if ($i < $retries)
            sleep($delaySeconds);
    }
    return $resp;
}
$inputData = json_decode(file_get_contents('php://input'), true);
$mode = $_POST['mode'] ?? ($inputData['mode'] ?? '');
if ($mode === 'clear') {
    unset($_SESSION['chat_history']);
    echo json_encode(["status" => "success", "message" => "Lịch sử chat đã được xóa."], JSON_UNESCAPED_UNICODE);
    exit;
}

function extractKeyword($text)
{
    $stopwords = ['tôi', 'muốn', 'biết', 'về', 'tin', 'tức', 'thông', 'tin', 'hãy', 'cho', 'các', 'bài', 'liên', 'quan', 'đến', 'ai', 'là', 'gì'];
    $words = preg_split('/[\s,\.]+/u', $text);
    $filtered = array_filter($words, fn($word) => !in_array(mb_strtolower($word, 'UTF-8'), $stopwords));
    return trim(implode(' ', $filtered));
}
$prompt = $_POST['prompt'] ?? ($inputData['prompt'] ?? '');
$history = $_SESSION['chat_history'] ?? [];
if (empty($prompt)) {
    echo json_encode(["status" => "error", "error" => "Không có nội dung gửi lên."]);
    exit;
}
if (preg_match('/(thứ mấy|ngày bao nhiêu|ngày mấy|mấy giờ|ngày hiện tại|hôm nay)/ui', $prompt)) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $weekdayNames = [
        'Monday' => 'Thứ Hai',
        'Tuesday' => 'Thứ Ba',
        'Wednesday' => 'Thứ Tư',
        'Thursday' => 'Thứ Năm',
        'Friday' => 'Thứ Sáu',
        'Saturday' => 'Thứ Bảy',
        'Sunday' => 'Chủ Nhật'
    ];
    $dayName = $weekdayNames[date('l')];
    $message = "Hôm nay là {$dayName}, ngày " . date('d/m/Y') . ", bây giờ là " . date('H:i') . ".";
    echo json_encode(["status" => "success", "message" => $message, "history" => $history]);
    exit;
}
if (empty($history)) {
    $history[] = [
        "role" => "user",
        "parts" => [
            [
                "text" => "Bạn là một AI chuyên về tin tức Việt Nam.
            Luôn trả lời bằng tiếng Việt, với phong cách ngắn gọn, dễ hiểu, thân thiện.
            Nhiệm vụ chính:
            - Tóm tắt, phân tích, và trả lời các câu hỏi liên quan đến tin tức, sự kiện, nhân vật, và xu hướng.
            - Với các câu hỏi đời thường (thời tiết, ngày tháng, sự kiện đang diễn ra), hãy cung cấp thông tin thực tế hoặc hướng dẫn cách tra cứu.
            - Nếu câu hỏi không liên quan đến tin tức hoặc kiến thức chung, hãy từ chối lịch sự.
            Hãy luôn giúp người dùng hiểu nhanh nội dung như một biên tập viên tin tức chuyên nghiệp."
            ]
        ]
    ];
}
$articles = [];
$host = "localhost";
$user = "root";
$pass = "";
$db = "crawl_news";
$conn = new mysqli($host, $user, $pass, $db);
if (!$conn->connect_error) {
    $keyword = extractKeyword($prompt);
    if (empty($keyword))
        $keyword = $prompt;
    $stmt = $conn->prepare("SELECT title, source, link, pubDate FROM crawl_news WHERE title LIKE ? ORDER BY pubDate DESC LIMIT 5");
    $like = "%" . $keyword . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc())
        $articles[] = $row;

    $stmt->close();
    $conn->close();
}
date_default_timezone_set('Asia/Ho_Chi_Minh');
$currentDate = date('d/m/Y H:i');
if (!empty($articles)) {
    $context = "Hôm nay là {$currentDate}. Dưới đây là các bài báo mới nhất từ cơ sở dữ liệu:\n\n";
    foreach ($articles as $a) {
        $context .= "- {$a['title']} (Nguồn: {$a['source']}, Ngày: {$a['pubDate']})\n";
        $context .= "  Link: {$a['link']}\n\n";
    }
    $combinedTitles = strtolower(json_encode($articles, JSON_UNESCAPED_UNICODE));
    if (strpos($combinedTitles, 'lương cường') !== false && strpos($prompt, 'chủ tịch') !== false) {
        $context .= "\nLưu ý: Theo dữ liệu mới nhất, ông **Lương Cường** là Chủ tịch nước Việt Nam hiện nay.\n";
    }
    $prompt = $context . "\nCâu hỏi của người dùng: " . $prompt;
}
$history[] = ["role" => "user", "parts" => [["text" => $prompt]]];
$requestData = [
    "contents" => $history,
    "generationConfig" => [
        "temperature" => 0.7,
        "topK" => 40,
        "topP" => 0.8,
        "maxOutputTokens" => 1024
    ]
];
$apiResponse = callGeminiApiWithRetry($requestData, $apiKey, $model, $caCertPath);
if (isset($apiResponse['error'])) {
    $aiMessage = (preg_match('/overloaded|quota/i', $apiResponse['error']))
        ? "Xin lỗi, hệ thống AI đang quá tải. Bạn vui lòng thử lại sau ít phút nhé!"
        : "Lỗi từ API: " . $apiResponse['error'];
} elseif (isset($apiResponse['candidates'][0]['content']['parts'])) {
    $parts = $apiResponse['candidates'][0]['content']['parts'];
    $aiMessage = implode("\n", array_column($parts, 'text'));
} else {
    $aiMessage = "Không có phản hồi văn bản từ AI !!!";
}
$history[] = ["role" => "model", "parts" => [["text" => $aiMessage]]];
if (count($history) > 10)
    $history = array_slice($history, -10);
$_SESSION['chat_history'] = $history;
echo json_encode([
    "status" => "success",
    "message" => $aiMessage,
    "history" => $history,
    "articles" => $articles
], JSON_UNESCAPED_UNICODE);
