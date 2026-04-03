<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ?module=admin&action=loginqtv");
    exit;
}

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/database.php';
global $conn;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0) {
    header("Location: ?module=admin&action=users");
    exit;
}

// LẤY THÔNG TIN USER
$sql = "SELECT * FROM users WHERE id = $id";
$editUser = getOne($sql);
if(!$editUser) {
    header("Location: ?module=admin&action=users");
    exit;
}

$msg = '';
$msgType = '';

// XỬ LÝ CẬP NHẬT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateData = [];
    
    // Tự động lặp qua các field được post lên
    foreach($_POST as $key => $val) {
        // Chỉ update nếu field đó tồn tại trong DB, không phải 'id' và không lấy 'password' thô
        if(array_key_exists($key, $editUser) && $key !== 'id' && $key !== 'password') {
            $updateData[$key] = trim($val);
        }
    }
    
    // Cập nhật mật khẩu nếu có nhập vào form
    if(!empty($_POST['password'])) {
        $updateData['password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    }
    
    if(!empty($updateData['fullname']) && !empty($updateData['email'])) {
        // Kiểm tra email trùng
        $email = $updateData['email'];
        $checkEmail = getOne("SELECT id FROM users WHERE email = '$email' AND id != $id");
        if($checkEmail) {
            $msg = "Email này đã được sử dụng bởi người dùng khác!";
            $msgType = "danger";
        } else {
            try {
                update('users', $updateData, "id = $id");
                $msg = "Cập nhật người dùng thành công!";
                $msgType = "success";
                // Cập nhật lại biến hiển thị
                $editUser = getOne($sql);
            } catch (Exception $e) {
                $msg = "Có lỗi xảy ra: " . $e->getMessage();
                $msgType = "danger";
            }
        }
    } else {
        $msg = "Vui lòng nhập các thông tin bắt buộc (Tên, Email).";
        $msgType = "warning";
    }
}

renderView('admin/users_edit', [
    'editUser' => $editUser,
    'msg' => $msg,
    'msgType' => $msgType
]);
