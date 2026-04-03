<?php
layout('admin_header');
layout('admin_sidebar');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Quản lý Người Dùng</h4>
            <p class="text-muted small mb-0">Xem, chỉnh sửa hoặc xóa thông tin người dùng khỏi hệ thống.</p>
        </div>
        <a href="?module=admin&action=users_add" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fa-solid fa-plus me-2"></i>Thêm Người Dùng Mới
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list-ul me-2 text-primary"></i>Danh sách Tài khoản</h6>
            
            <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control form-control-sm bg-light border-0" placeholder="Tìm kiếm...">
                <button class="btn btn-sm btn-light border-0"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Thông Tin</th>
                            <th>Vai Trò</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Tạo</th>
                            <th class="text-end pe-4">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if(!empty($users)): ?>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= $user['id'] ?? '?' ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                                <?= strtoupper(substr($user['fullname'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($user['fullname'] ?? 'Chưa cập nhật') ?></h6>
                                                <small class="text-muted"><?= htmlspecialchars($user['email'] ?? 'Không có email') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if(isset($user['role']) && $user['role'] == 'admin'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">
                                            <i class="fa-solid fa-circle-check me-1 small"></i>Hoạt động
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?= isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : '---' ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="?module=admin&action=users_edit&id=<?= $user['id'] ?? '' ?>" class="btn btn-sm btn-light text-primary rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 32px;"><i class="fa-solid fa-pen"></i></a>
                                        
                                        <?php if((int)($user['id'] ?? 0) !== (int)($_SESSION['user_id'] ?? -1)): ?>
                                            <a href="?module=admin&action=users&delete_id=<?= $user['id'] ?? '' ?>" 
                                               class="btn btn-sm btn-light text-danger rounded-circle" 
                                               style="width: 32px; height: 32px; padding: 0; line-height: 32px;"
                                               onclick="return confirm('Bạn có chắc muốn xoá người dùng này? Thao tác không thể hoàn tác.');">
                                               <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fs-1 text-light mb-3"></i>
                                    <p class="mb-0">Chưa có dữ liệu người dùng</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 p-3 border-top">
                <small class="text-muted">Hiển thị <?php echo count($users); ?> người dùng</small>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link border-0 text-muted" href="#">Trước</a></li>
                        <li class="page-item active"><a class="page-link rounded-circle border-0 d-flex align-items-center justify-content-center mx-1 shadow-sm" style="width: 30px; height: 30px;" href="#">1</a></li>
                        <li class="page-item"><a class="page-link rounded-circle border-0 text-dark d-flex align-items-center justify-content-center mx-1" style="width: 30px; height: 30px;" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 text-muted" href="#">Sau</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php layout('admin_footer'); ?>
