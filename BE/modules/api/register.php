<?php
if(!defined('_TAI')){
    die('Truy cap khong hop le');
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
    'msg' => 'Da co loi xay ra',
    'errors' => []
];

if(isPost()){
    global $conn;
    $rawLogData = file_get_contents("php://input");
    $data = json_decode($rawLogData, true);
    
    $fullname = trim($data['fullname'] ?? $_POST['fullname'] ?? '');
    $email = trim($data['email'] ?? $_POST['email'] ?? '');
    $password = trim($data['password'] ?? $_POST['password'] ?? '');
    $confirm_password = trim($data['confirm_password'] ?? $_POST['confirm_password'] ?? $password);

    $errors = [];
    
    if(empty($fullname)){
        $errors['fullname'] = 'Ho ten bat buoc phai nhap';
    }else{
        if(mb_strlen($fullname) < 5){
            $errors['fullname'] = 'Ho ten phai hon 5 ki tu';
        }
    }
    
    if(empty($email)){
        $errors['email'] = 'Email bat buoc phai nhap';
    }else {
        if(!validateEmail($email)){
            $errors['email'] = 'Email khong dung dinh dang';
        }else{
            $checkEmail = getRows("SELECT * FROM users WHERE email = '$email'");
            if($checkEmail > 0){ 
                $errors['email'] = 'Email da duoc su dung';
            }
        }
    }
    
    if(empty($password)){
        $errors['password'] = 'Mat khau bat buoc phai nhap';
    }else {
        if (strlen($password) < 6){
            $errors['password'] = 'Mat khau phai lon hon 6 ki tu';
        }
    }
    
    if(empty($password)){
        $errors['confirm_password'] = 'Vui long nhap lai mat khau';
    }else {
        if ($password !== $confirm_password){
            $errors['confirm_password'] = 'Mat khau nhap vao khong khop';
        }
    }

    if(empty($errors)) {
        $active_token = sha1(uniqid().time());
        $dbData = [
            'fullname' => $fullname,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'active_token' => $active_token,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $insertStatus = insert('users', $dbData);
        if($insertStatus){
            $emailTo = $email;
            $subject = 'Kich hoat tai khoan he thong Tai!!';
            $content = 'Chuc mung ban da dang ky thanh cong tai khoan tai Tai. </br>';
            $content .= 'De kich hoat tai khoan ban hay click vao duong link ben duoi: </br>';
            $content .= _HOST_URL . '/?module=auth&action=active&token='.$active_token.'</br>';
            $content .= 'Cam on ban da ung ho Tai!!!';
            
            try {
                ob_clean();
                sendMail($emailTo, $subject, $content);
                $smtpOutput = ob_get_clean();

                if (!empty($smtpOutput) && strpos($smtpOutput, 'Gui that bai') !== false) {
                    $response['status'] = 'success';
                    $response['msg'] = 'Dang ky thanh cong! Ban co the dang nhap ngay.';
                    update('users', ['status' => 1], "email = '" . $conn->real_escape_string($email) . "'");
                } else {
                    $response['status'] = 'success';
                    $response['msg'] = 'Dang ky thanh công! Vui lòng kiem tra email de kich hoat.';
                }
            } catch (Exception $e) {
                $response['status'] = 'success';
                $response['msg'] = 'Dang ky thanh cong! Ban co the dang nhap ngay.';
                update('users', ['status' => 1], "email = '" . $conn->real_escape_string($email) . "'");
            }
        }
    } else {
        $response['msg'] = 'Vui long kiem tra lai thong tin nhap vao';
        $response['errors'] = $errors;
    }
} else {
    $response['msg'] = 'Phuong thuc khong hop le';
}

echo json_encode($response);
exit;
