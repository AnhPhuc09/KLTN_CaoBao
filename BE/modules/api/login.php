<?php
if(!defined('_TAI')){
    die('Truy cập không hợp lệ');
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

$response = [
    'status' => 'error',
    'msg' => 'Đã có lỗi xảy ra'
];

if(isPost()){
    $rawLogData = file_get_contents("php://input");
    $data = json_decode($rawLogData, true);
    
    $email = trim($data['email'] ?? $_POST['email'] ?? '');
    $password = trim($data['password'] ?? $_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $response['msg'] = 'Vui lòng nhập đầy đủ Email và Mật khẩu';
    } else {
        $user = getOne("SELECT * FROM users WHERE email = '$email'");

        if (!empty($user)) {
            if ($user['status'] == 0) {
                $response['msg'] = 'Tài khoản của bạn hiện đang bị khoá hoặc chưa kích hoạt';
            } else {
                if (password_verify($password, $user['password'])) {
                    $token = sha1(uniqid() . time());
                    
                    $tokenData = [
                        'token' => $token,
                        'user_id' => $user['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    insert('token_login', $tokenData);

                    $response['status'] = 'success';
                    $response['msg'] = 'Đăng nhập thành công';
                    $response['data'] = [
                        'token' => $token,
                        'name' => $user['fullname'],
                        'avatar' => $user['avatar'] ?? '',
                        'role' => $user['role'] ?? 'user'
                    ];
                } else {
                    $response['msg'] = 'Mật khẩu nhập vào không chính xác';
                }
            }
        } else {
            $response['msg'] = 'Địa chỉ Email này chưa được đăng ký trong hệ thống';
        }
    }
} else {
    $response['msg'] = 'Phương thức không hợp lệ';
}

echo json_encode($response);
exit;
