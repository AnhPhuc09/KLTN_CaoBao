<?php
layout('admin_header');
layout('admin_sidebar');

// Require DB connection for stats if needed, or assume data is passed via Controller.
// For now, we use dummy data for the beautiful UI. 
?>

<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white" style="border-radius: 15px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-1">Xin chào, <?= isset($admin_name) ? $admin_name : 'Quản trị viên' ?>! 👋</h3>
                    <p class="mb-0 text-white-50">Chào mừng bạn quay trở lại. Chúc bạn một ngày làm việc hiệu quả.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-primary h-100 shadow-sm border-0">
                <div class="stat-card-title">Tổng Người Dùng</div>
                <div class="stat-card-value">1,245</div>
                <i class="fa-solid fa-users stat-card-icon"></i>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-success h-100 shadow-sm border-0">
                <div class="stat-card-title">Tổng Bài Báo</div>
                <div class="stat-card-value">342</div>
                <i class="fa-solid fa-newspaper stat-card-icon"></i>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-warning h-100 shadow-sm border-0">
                <div class="stat-card-title">Bình Luận Mới</div>
                <div class="stat-card-value">89</div>
                <i class="fa-solid fa-comments stat-card-icon"></i>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card bg-gradient-danger h-100 shadow-sm border-0">
                <div class="stat-card-title">Lượt Truy Cập Hôm Nay</div>
                <div class="stat-card-value">4,521</div>
                <i class="fa-solid fa-chart-line stat-card-icon"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-bolt me-2"></i>Hoạt Động Gần Đây</h6>
                    <button class="btn btn-sm btn-outline-primary">Xem tất cả</button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-4 py-3 border-bottom-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-user-plus"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Người dùng mới đăng ký</h6>
                                    <p class="mb-1 text-muted small">Tài khoản <span class="text-dark fw-bold">nguyenvana@gmail.com</span> vừa tạo cách đây 5 phút.</p>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>Hôm nay, 10:25 AM</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-4 py-3 border-bottom-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-success text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-newspaper"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Bài báo mới được xuất bản</h6>
                                    <p class="mb-1 text-muted small">Bài viết "<span class="text-dark fw-bold">Công nghệ AI thay đổi thế giới</span>" vừa được duyệt.</p>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>Hôm qua, 15:40 PM</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-rocket me-2"></i>Thao Tác Nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="?module=news&action=add" class="btn btn-primary text-start px-4 py-3 d-flex justify-content-between align-items-center rounded-3">
                            <span class="fw-bold"><i class="fa-solid fa-plus me-2"></i>Thêm Bài Báo Mới</span>
                            <i class="fa-solid fa-chevron-right opacity-50"></i>
                        </a>
                        <a href="?module=users&action=add" class="btn btn-outline-primary text-start px-4 py-3 d-flex justify-content-between align-items-center rounded-3">
                            <span class="fw-bold"><i class="fa-solid fa-user-plus me-2"></i>Thêm Người Dùng</span>
                            <i class="fa-solid fa-chevron-right opacity-50"></i>
                        </a>
                        <a href="?module=admin&action=comments" class="btn btn-outline-warning text-start px-4 py-3 d-flex justify-content-between align-items-center rounded-3">
                            <span class="fw-bold"><i class="fa-solid fa-comments me-2"></i>Duyệt Bình Luận <span class="badge bg-danger ms-2 rounded-pill">12</span></span>
                            <i class="fa-solid fa-chevron-right opacity-50"></i>
                        </a>
                        <a href="crawl_database.php" class="btn btn-outline-success text-start px-4 py-3 d-flex justify-content-between align-items-center rounded-3">
                            <span class="fw-bold"><i class="fa-solid fa-spider me-2"></i>Chạy Thu Thập Dữ Liệu</span>
                            <i class="fa-solid fa-chevron-right opacity-50"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php layout('admin_footer'); ?>
