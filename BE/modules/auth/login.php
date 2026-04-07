<?php
if (!defined('_TAI')) {
    die('Truy cập không hợp lệ');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = ['title' => 'Đăng nhập hệ thống'];
layout('header-auth', $data);

$client_id = "406500628615-c725efu1d7ijrg41ekuuv0m32uvqdafo.apps.googleusercontent.com";
$redirect_uri = 'http://localhost/KLTN_CaoBao/BE/?module=auth&action=google_callback';
$scope = "email profile";

$google_login_url = "https://accounts.google.com/o/oauth2/v2/auth?"
    . "client_id={$client_id}"
    . "&redirect_uri=" . urlencode($redirect_uri)
    . "&response_type=code"
    . "&scope=" . urlencode($scope)
    . "&access_type=offline"
    . "&state=legacy";

if (isPost()) {
    $filter = filterData();
    $errors = [];

    if (empty(trim($filter['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!validateEmail(trim($filter['email']))) {
            $errors['email']['isEmail'] = 'Email không đúng định dạng';
        }
    }

    if (empty(trim($filter['password']))) {
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
    } else {
        if (strlen(trim($filter['password'])) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 kí tự';
        }
    }

    if (empty($errors)) {
        $email = $filter['email'];
        $password = $filter['password'];
        $checkEmail = getOne("SELECT * FROM users WHERE email = '$email'");

        if (!empty($checkEmail)) {
            if (password_verify($password, $checkEmail['password'])) {
                $token = sha1(uniqid() . time());
                setSession('token_login', $token);
                $_SESSION['user_id'] = $checkEmail['id'];

                $data = [
                    'token' => $token,
                    'created_at' => date('Y-m-d H:i:s'),
                    'user_id' => $checkEmail['id']
                ];
                $insertToken = insert('token_login', $data);

                if ($insertToken) {
                    redirect('?module=news&action=list');
                } else {
                    setSessionFlash('msg', 'Đăng nhập không thành công.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Email hoặc mật khẩu không đúng.');
                setSessionFlash('msg_type', 'danger');
            }
        } else {
            setSessionFlash('msg', 'Tài khoản không tồn tại.');
            setSessionFlash('msg_type', 'danger');
        }
    } else {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
    }
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');

renderView('auth/login', [
    'msg' => $msg,
    'msg_type' => $msg_type,
    'oldData' => $oldData,
    'errorsArr' => $errorsArr,
    'google_login_url' => $google_login_url
]);