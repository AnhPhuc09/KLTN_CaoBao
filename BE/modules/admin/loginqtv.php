<?php
require_once __DIR__ . '/../../includes/session.php';

$error_msg = "";

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    header("Location: ?module=admin&action=dashboard");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $pass  = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $user = getOne($sql);
    if ($user) {
        if (password_verify($pass, $user['password'])) {
            if ($user['role'] === 'admin') {
                // Đăng nhập thành công -> Lưu session
                setSession('user_id', $user['id']);
                setSession('user_name', $user['fullname']);
                setSession('user_role', 'admin');

                header("Location: ?module=admin&action=dashboard");
                exit;
            } else {
                $error_msg = "Tài khoản không có quyền Admin!";
            }
        } else {
            $error_msg = "Sai mật khẩu!";
        }
    } else {
        $error_msg = "Email không tồn tại!";
    }
}
renderView('admin/loginqtv', ['error_msg' => $error_msg]);