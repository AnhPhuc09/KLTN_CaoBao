<?php
if (!defined('_TAI')) {
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
    'msg' => 'Đã có lỗi xảy ra.'
];

if (isPost()) {
    $rawLogData = file_get_contents("php://input");
    $data = json_decode($rawLogData, true);
    
    $email = !empty($data['email']) ? $data['email'] : (!empty($_POST['email']) ? $_POST['email'] : '');
    $email = trim($email);

    if (empty($email)) {
        $response['msg'] = 'Email bắt buộc phải nhập.';
    } elseif (!validateEmail($email)) {
        $response['msg'] = 'Email không đúng định dạng.';
    } else {
        $checkEmail = getOne("SELECT * FROM users WHERE email = '$email'");

        if (!empty($checkEmail)) {
            $forgot_token = sha1(uniqid() . time());
            $updateData = ['forget_token' => $forgot_token];
            $condition = "id=" . $checkEmail['id'];

            $updateStatus = update('users', $updateData, $condition);
            if ($updateStatus) {
                $emailTo = $email;
                $subject = 'Reset mật khẩu tài khoản hệ thống Tai!!';
                $content = 'Bạn đang yêu cầu reset mật khẩu tại Tai. <br>';
                $content .= 'Để thay đổi mật khẩu, hãy click vào đường link bên dưới: <br>';
                $content .= _HOST_URL . '/?module=auth&action=reset&token=' . $forgot_token . '<br>';
                $content .= 'Cảm ơn bạn đã ủng hộ Tai!!!';

                try {
                    ob_clean();
                    sendMail($emailTo, $subject, $content);
                    $smtpOutput = ob_get_clean();

                    if (!empty($smtpOutput) && strpos($smtpOutput, 'Gửi thất bại') !== false) {
                        $response['status'] = 'error';
                        $response['msg'] = 'Hệ thống gửi thư đang gặp sự cố kết nối. Vui lòng thử lại sau ít phút hoặc liên hệ quản trị viên.';
                    } else {
                        $response['status'] = 'success';
                        $response['msg'] = 'Yêu cầu thành công! Vui lòng kiểm tra hộp thư (bao gồm cả thư rác) để đặt lại mật khẩu.';
                    }
                } catch (Exception $e) {
                    $response['status'] = 'error';
                    $response['msg'] = 'Không thể gửi email lúc này. Vui lòng kiểm tra lại kết nối mạng.';
                }
            } else {
                $response['msg'] = 'Đã có lỗi cơ sở dữ liệu. Vui lòng thử lại sau!';
            }
        } else {
            $response['msg'] = 'Địa chỉ email này chưa được đăng ký trong hệ thống!';
        }
    }
} else {
    $response['msg'] = 'Phương thức không được hỗ trợ.';
}

echo json_encode($response);
exit;
