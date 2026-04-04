<?php
if(!defined('_TAI')){
    die('Truy cập không hợp lệ');
}
$data = [
    'title' => 'Đăng ký tài khoản'
];
layout('header-auth',$data);

if(isPost()){
    $filter = filterData();
    $errors = [];
    if(empty(trim($filter['fullname']))){
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    }else{
        if(strlen(trim(($filter['fullname'])))<5){
            $errors['fullname']['length'] = 'Họ tên phải hơn 5 kí tự';
        }
    }
    if(empty(trim($filter['email']))){
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    }else {
        if(!validateEmail(trim($filter['email']))){
        $errors['email']['isEmail'] = 'Email không đúng định dạng';
        }else{
            $email = $filter['email'];
            $checkEmail = getRows("SELECT * FROM users WHERE email = '$email'");
            if($checkEmail > 0){ 
                $errors['email']['check'] = 'Email đã tồn tại';
            }
        }
    }
    if(empty(trim($filter['password']))){
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
    }else {
        if (strlen(trim($filter['password']))<6){
            $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 kí tự';
        }
    }
    if(empty(trim($filter['password']))){
        $errors['confirm_password']['required'] = 'Vui lòng nhập lại mật khẩu';
    }else {
        if (trim($filter['password']) !== trim($filter['confirm_password'])){
            $errors['confirm_password']['like'] = 'Mật khẩu nhập vào không khớp';
        }
    }

    if(empty($errors)) {
        $active_token = sha1(uniqid().time());
        $data = [
            'fullname' => $filter['fullname'],
            'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
            'email' => $filter['email'],
            'active_token' => $active_token,
            'created_at' => date('Y:m:d H:i:s')
        ];
        $insertStatus = insert('users', $data);
        if($insertStatus){
            $emailTo = $filter['email'];
            $subject = 'Kích hoạt tài khoản hệ thống Tai!!';
            $content = 'Chúc mừng bạn đã đăng ký thành công tài khoản tại Tai. </br>';
            $content .= 'Để kích hoạt tài khoản bạn hãy click vào đường link bên dưới: </br>';
            $content .= _HOST_URL . '/?module=auth&action=active&token='.$active_token.'</br>';
            $content .= 'Cảm ơn bạn đã ủng hộ Tai!!!';
            sendMail($emailTo, $subject, $content);

            setSessionFlash('msg', 'Đăng ký thành công vui lòng kích hoạt tài khoản');
            setSessionFlash('msg_type', 'success');
        } else {
            setSessionFlash('msg', 'Đăng ký không thành công vui lòng thử lại sau');
            setSessionFlash('msg_type', 'danger');
        }
    } else {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);

    }

    $errorsArr =  getSessionFlash('errors');
}

renderView('auth/register', [
    'msg' => $msg,
    'msg_type' => $msg_type,
    'oldData' => $oldData,
    'errorsArr' => $errorsArr ?? []
]);

layout('footer');