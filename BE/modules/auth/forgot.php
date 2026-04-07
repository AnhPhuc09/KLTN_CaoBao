<?php
if (!defined('_TAI')) {
    die('Truy cập không hợp lệ');
}
$data = [
    'title' => 'Quên mật khẩu'
];
layout('header-auth', $data);

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

    if (empty($errors)) {
        $email = $filter['email'];
        $checkEmail = getOne("SELECT * FROM users WHERE email = '$email'");

        if (!empty($checkEmail)) {
            $forgot_token = sha1(uniqid() . time());
            $data = ['forget_token' => $forgot_token];
            $condition = "id=" . $checkEmail['id'];

            $updateStatus = update('users', $data, $condition);
            if ($updateStatus) {
                $emailTo = $email;
                $subject = 'Reset mật khẩu tài khoản hệ thống Tai!!';
                $content = 'Bạn đang yêu cầu reset mật khẩu tại Tai. <br>';
                $content .= 'Để thay đổi mật khẩu, hãy click vào đường link bên dưới: <br>';
                $content .= _HOST_URL . '/?module=auth&action=reset&token=' . $forgot_token . '<br>';
                $content .= 'Cảm ơn bạn đã ủng hộ Tai!!!';

                sendMail($emailTo, $subject, $content);
                setSessionFlash('msg', 'Gửi yêu cầu thành công, vui lòng kiểm tra email.');
                setSessionFlash('msg_type', 'success');
            } else {
                setSessionFlash('msg', 'Đã có lỗi xảy ra. Vui lòng thử lại sau!');
                setSessionFlash('msg_type', 'danger');
            }
        } else {
            setSessionFlash('msg', 'Địa chỉ email này chưa được đăng ký trong hệ thống!');
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

renderView('auth/forgot', [
    'msg' => $msg,
    'msg_type' => $msg_type,
    'oldData' => $oldData,
    'errorsArr' => $errorsArr
]);

layout('footer');