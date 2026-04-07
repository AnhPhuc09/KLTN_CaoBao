<?php
if(!defined('_TAI')) {
    die('Truy cập không hợp lệ');
}

$host = defined('_HOST') ? _HOST : "localhost";
$user = defined('_USER') ? _USER : "root";
$pass = defined('_PASS') ? _PASS : "";
$db   = defined('_DB') ? _DB : "crawl_news";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

function getAll($sql) {
    global $conn;
    $result = $conn->query($sql);
    if(!$result) {
        die("Lỗi query: " . $conn->error);
    }
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getRows($sql) {
    global $conn;
    $result = $conn->query($sql);
    if(!$result) {
        die("Lỗi query: " . $conn->error);
    }
    return $result->num_rows;
}

function getOne($sql) {
    global $conn;
    $result = $conn->query($sql);
    if(!$result) {
        die("Lỗi query: " . $conn->error);
    }
    return $result->fetch_assoc();
}

function insert($table, $data) {
    global $conn;
    $columns = implode(", ", array_keys($data));
    $values  = "'" . implode("','", array_map([$conn, 'real_escape_string'], $data)) . "'";
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    $rel = $conn->query($sql);
    if(!$rel) {
        die("Lỗi insert: " . $conn->error);
    }
    return $rel;
}

function update($table, $data, $condition = '') {
    global $conn;
    $updateArr = [];
    foreach ($data as $key => $value) {
        if ($value === null) {
            $updateArr[] = "$key=NULL";
        } else {
            $updateArr[] = "$key='" . $conn->real_escape_string((string)$value) . "'";
        }
    }
    
    $update = implode(", ", $updateArr);

    $sql = "UPDATE $table SET $update";
    if(!empty($condition)) {
        $sql .= " WHERE $condition";
    }

    $rel = $conn->query($sql);
    if(!$rel) {
        die("Lỗi update: " . $conn->error);
    }
    return $rel;
}

function delete($table, $condition = '') {
    global $conn;
    $sql = "DELETE FROM $table";
    if(!empty($condition)) {
        $sql .= " WHERE $condition";
    }
    $rel = $conn->query($sql);
    if(!$rel) {
        die("Lỗi delete: " . $conn->error);
    }
    return $rel;
}

function lastID() {
    global $conn;
    return $conn->insert_id;
}
?>