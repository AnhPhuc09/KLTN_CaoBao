<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - KLTN_CaoBao</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f0f2f5;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
        display: flex;
        background-color: #fff;
    }

    .login-image {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        padding: 3rem;
    }

    .login-image i {
        font-size: 5rem;
        margin-bottom: 1.5rem;
    }

    .login-image h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .login-form-container {
        width: 50%;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-form-container h3 {
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control {
        padding: 0.8rem 1rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }

    .btn-primary {
        padding: 0.8rem 1rem;
        border-radius: 8px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .login-image {
            display: none;
        }

        .login-form-container {
            width: 100%;
            padding: 2rem;
        }

        .login-card {
            max-width: 400px;
            margin: 0 1rem;
        }
    }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-image">
            <i class="fa-solid fa-shield-halved"></i>
            <h2>Admin Panel</h2>
            <p class="text-center opacity-75 mt-2">Hệ thống quản trị nội dung phân tích tự động</p>
        </div>
        <div class="login-form-container">
            <h3>Đăng nhập</h3>
            <p class="text-muted mb-4">Vui lòng nhập thông tin tài khoản của bạn</p>

            <?php if(!empty($error_msg)): ?>
            <div class="alert alert-danger rounded-3" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <?= $error_msg ?>
            </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Email đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="fa-solid fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0"
                            placeholder="admin@example.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0"
                            placeholder="Nhập mật khẩu..." required>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label small" for="rememberMe">Nhớ mật khẩu</label>
                    </div>
                    <a href="#" class="small text-decoration-none">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>

            <div class="text-center mt-4">
                <a href="<?php echo _HOST_URL; ?>?module=home" class="text-muted small text-decoration-none"><i
                        class="fa-solid fa-arrow-left me-1"></i> Trở về trang chủ</a>
            </div>
        </div>
    </div>

</body>

</html>