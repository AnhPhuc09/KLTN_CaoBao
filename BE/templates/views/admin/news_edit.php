<?php
layout('admin_header');
layout('admin_sidebar');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Chỉnh sửa Bài Báo</h4>
            <p class="text-muted small mb-0">Cập nhật nội dung, hình ảnh hoặc chuyên mục.</p>
        </div>
        <a href="?module=admin&action=news" class="btn btn-light shadow-sm rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Trở về danh sách
        </a>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show rounded-4" role="alert">
            <?php if($msgType == 'success'): ?>
                <i class="fa-solid fa-circle-check me-2"></i>
            <?php else: ?>
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <?php endif; ?>
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-pen-to-square me-2"></i>Nội dung bài viết</h6>
                </div>
                <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Tiêu đề (Title) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" name="title" value="<?= htmlspecialchars($article['title'] ?? '') ?>" required placeholder="Nhập tiêu đề bài báo...">
                        </div>
                        
                        <?php if(array_key_exists('description', $article)): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Mô tả ngắn (Description)</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Tóm tắt ngắn gọn bài báo..."><?= htmlspecialchars($article['description'] ?? '') ?></textarea>
                        </div>
                        <?php endif; ?>

                        <?php if(array_key_exists('content', $article)): ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Nội dung chi tiết (Content)</label>
                            <!-- Sử dụng textarea nếu không có editor -->
                            <textarea class="form-control" name="content" rows="12" placeholder="Nhập nội dung đầy đủ..."><?= htmlspecialchars($article['content'] ?? '') ?></textarea>
                            <small class="text-muted mt-2 d-block"><i class="fa-solid fa-circle-info me-1"></i>Hỗ trợ chèn HTML tĩnh. Nên tích hợp CKEditor/TinyMCE sau.</small>
                        </div>
                        <?php endif; ?>
                        
                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu Thay Đổi
                            </button>
                        </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <!-- Sidebar Form Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-gear me-2 text-secondary"></i>Thuộc tính</h6>
                </div>
                <div class="card-body p-4">
                    <?php if(array_key_exists('thumbnail', $article)): ?>
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold small text-muted d-block text-start">Ảnh đại diện (Thumbnail URL)</label>
                            <div class="bg-light rounded-3 overflow-hidden mb-3 d-flex align-items-center justify-content-center border" style="height: 180px;">
                                <?php if(!empty($article['thumbnail'])): ?>
                                    <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="Thumbnail" class="img-fluid" style="max-height: 100%; width: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted"><i class="fa-regular fa-image fs-1 mb-2 d-block"></i>Chưa có ảnh</span>
                                <?php endif; ?>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="thumbnail" value="<?= htmlspecialchars($article['thumbnail'] ?? '') ?>" placeholder="https://...">
                        </div>
                    <?php endif; ?>

                    <?php if(array_key_exists('category_id', $article)): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Chuyên mục (Category)</label>
                            <select class="form-select" name="category_id">
                                <option value="0">--- Chọn chuyên mục ---</option>
                                <?php if(!empty($categories)): ?>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($article['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name'] ?? 'Không tên') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="<?= $article['category_id'] ?>" selected>Chuyên mục ID: <?= $article['category_id'] ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if(array_key_exists('view', $article)): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Lượt xem (Views)</label>
                            <input type="number" class="form-control" name="view" value="<?= (int)($article['view'] ?? 0) ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="border-top pt-3 mt-4">
                        <small class="text-muted d-block mb-1">Ngày tạo: <strong><?= isset($article['created_at']) ? date('d/m/Y H:i', strtotime($article['created_at'])) : 'Không rõ' ?></strong></small>
                        <small class="text-muted d-block">Lần sửa cuối: <strong><?= isset($article['updated_at']) ? date('d/m/Y H:i', strtotime($article['updated_at'])) : 'Vừa xong' ?></strong></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<?php layout('admin_footer'); ?>
