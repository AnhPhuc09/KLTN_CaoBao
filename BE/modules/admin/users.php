<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ?module=admin&action=loginqtv");
    exit;
}

require_once __DIR__ . '/../../includes/session.php';
global $conn;
if (!isset($conn)) $conn = new mysqli("localhost", "root", "", "crawl_news");

$sql = "SELECT * FROM users ORDER BY id DESC";
$users = getAll($sql); 
if(empty($users)) {
    $result = $conn->query($sql);
    $users = [];
    if($result) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
}

if(isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
 
    if($del_id !== (int)$_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id = $del_id");
        header("Location: ?module=admin&action=users");
        exit;
    }
}

renderView('admin/users', [
    'users' => $users
]);
