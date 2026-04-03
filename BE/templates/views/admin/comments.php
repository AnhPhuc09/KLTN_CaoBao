<?php
layout('admin_header');
layout('admin_sidebar');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Quản lý Bình Luận</h4>
            <p class="text-muted small mb-0">Kiểm duyệt các bình luận của người dùng trên hệ thống.</p>
        </div>
        <div>
            <button class="btn btn-outline-danger shadow-sm rounded-pill px-4 me-2">
                <i class="fa-solid fa-trash-can me-2"></i>Xóa nhiều
            </button>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold"><i class="fa-solid fa-comments me-2 text-primary"></i>Danh sách bình luận mới nhất</h6>
            
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm bg-light border-0" style="width: 150px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Đã duyệt</option>
                    <option value="0">Chưa duyệt</option>
                </select>
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control form-control-sm bg-light border-0" placeholder="Tìm nội dung...">
                    <button class="btn btn-sm btn-light border-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" width="5%">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                </div>
                            </th>
                            <th width="20%">Người Bình Luận</th>
                            <th width="35%">Nội Dung</th>
                            <th width="20%">Bài Báo</th>
                            <th width="10%">Ngày Gửi</th>
                            <th class="text-end pe-4" width="10%">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if(!empty($comments)): ?>
                            <?php foreach($comments as $cmt): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="form-check m-0">
                                            <input class="form-check-input cmt-check" type="checkbox" value="<?= $cmt['id'] ?? '' ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold;">
                                                <?= strtoupper(substr($cmt['fullname'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;"><?= htmlspecialchars($cmt['fullname'] ?? 'Ẩn danh') ?></h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">ID: #<?= $cmt['user_id'] ?? '?' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-dark" style="font-size: 0.9rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= htmlspecialchars($cmt['content'] ?? 'Không có nội dung') ?>
                                        </p>
                                    </td>
                                    <td>
                                        <a href="?module=news&action=detail&id=<?= $cmt['news_id'] ?? '' ?>" class="text-decoration-none text-primary small fw-medium" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" target="_blank">
                                            <i class="fa-solid fa-link me-1"></i><?= htmlspecialchars($cmt['news_title'] ?? 'Bài báo #'.$cmt['news_id']) ?>
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        <?= isset($cmt['created_at']) ? date('d/m H:i', strtotime($cmt['created_at'])) : '---' ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#" class="btn btn-sm btn-light text-primary rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" title="Trả lời/Chi tiết"><i class="fa-solid fa-reply"></i></a>
                                        
                                        <a href="?module=admin&action=comments&delete_id=<?= $cmt['id'] ?? '' ?>" class="btn btn-sm btn-light text-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" title="Xoá" onclick="return confirm('Bạn có chắc muốn xoá bình luận này?');"><i class="fa-solid fa-trash-can"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3 text-secondary" style="font-size: 3rem;"><i class="fa-regular fa-comments text-opacity-25"></i></div>
                                    <h5 class="fw-bold text-dark text-opacity-50">Chưa có bình luận nào</h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 p-3 border-top bg-light bg-opacity-50">
                <small class="text-muted">Hiển thị <?php echo count($comments); ?> bình luận</small>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link border-0 text-muted bg-transparent" href="#">Trước</a></li>
                        <li class="page-item active"><a class="page-link rounded-circle border-0 d-flex align-items-center justify-content-center mx-1 shadow-sm" style="width: 30px; height: 30px;" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 text-muted bg-transparent" href="#">Sau</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all logic
    const checkAll = document.getElementById('checkAll');
    if(checkAll) {
        checkAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.cmt-check');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>

<?php layout('admin_footer'); ?>
