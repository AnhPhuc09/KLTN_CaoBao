<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/database.php';
require_once 'includes/session.php';

$client_id = "406500628615-c725efu1d7ijrg41ekuuv0m32uvqdafo.apps.googleusercontent.com";
$client_secret = "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";
$redirect_uri = 'http://localhost/KLTN_CaoBao/BE/?module=auth&action=google_callback';
if (isset($_GET['code'])) {
    $token_url = "https://oauth2.googleapis.com/token";
    $post_data = [
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $token_url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query($post_data),
        CURLOPT_SSL_VERIFYPEER => false
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (!empty($data['access_token'])) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://www.googleapis.com/oauth2/v2/userinfo",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $data['access_token']],
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $userinfo = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!empty($userinfo['email'])) {
            $email = $userinfo['email'];
            $name = $userinfo['name'] ?? 'Người dùng Google';
            $checkUser = getOne("SELECT * FROM users WHERE email = '$email'");
            if (empty($checkUser)) {
                $newUser = [
                    'email' => $email,
                    'fullname' => $name,
                    'password' => password_hash(uniqid(), PASSWORD_DEFAULT),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                if (insert('users', $newUser)) {
                    $userId = lastID();
                    if (!$userId) {
                        $newUserCheck = getOne("SELECT id FROM users WHERE email = '$email'");
                        $userId = $newUserCheck['id'];
                    }
                } else {
                    die("Lỗi tạo người dùng mới trong database.");
                }
            } else {
                $userId = $checkUser['id'];
            }
            $_SESSION['user_id'] = $userId;
            $token = sha1(uniqid() . time());
            setSession('token_login', $token);
            $tokenData = [
                'token' => $token,
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ];
            insert('token_login', $tokenData);
            redirect('/?module=news&action=list');
            exit;
        } else {
            echo "Không thể lấy thông tin người dùng từ Google.";
            exit;
        }
    } else {
        echo "Không thể lấy token từ Google. Chi tiết lỗi: " . htmlspecialchars(json_encode($data));
        exit;
    }
} else {
    echo "Mã xác thực không hợp lệ.";
    exit;
}
