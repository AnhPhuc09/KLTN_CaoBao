<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crawl_news";

$sqlFile = __DIR__ . "/crawl_news.sql";
if (!file_exists($sqlFile)) {
    die("Không tìm thấy file SQL!");
}

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$dbCheck = $conn->query("SHOW DATABASES LIKE '$dbname'");
if ($dbCheck && $dbCheck -> num_rows > 0) {
    echo "Database `$dbname` đã tồn tại.<br>";
    $conn->select_db($dbname);
    $dropTables = ["comments", "favourite_news", "crawl_news", "token_login", "users"];
    foreach ($dropTables as $table) {
        $conn->query("DROP TABLE IF EXISTS `$table`");
        echo "Đã xóa bảng `$table` (nếu có).<br>";
    }
} else {
    if ($conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci") === TRUE) {
        echo "Database `$dbname` đã được tạo.<br>";
    } else {
        die("Lỗi tạo DB: " . $conn->error);
    }
    $conn->select_db($dbname);
}

$sql = file_get_contents($sqlFile);
if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Import file SQL thành công!";
} else {
    echo "Lỗi khi import: " . $conn->error;
}

$conn->close();
?>
