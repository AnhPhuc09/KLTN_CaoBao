<?php
layout('admin_header');
layout('admin_sidebar');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Quản lý Bài Báo</h4>
            <p class="text-muted small mb-0">Quản lý, thao tác với các bài báo trong cơ sở dữ liệu.</p>
        </div>
        <div>
            <a href="crawl_database.php" class="btn btn-outline-success shadow-sm rounded-pill px-4 me-2">
                <i class="fa-solid fa-spider me-2"></i>Crawl Dữ Liệu
            </a>
            <a href="?module=admin&action=news_add" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fa-solid fa-plus me-2"></i>Thêm Mới
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list-ul me-2 text-primary"></i>Danh sách bài báo</h6>
            
            <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control form-control-sm bg-light border-0" placeholder="Tìm kiếm bài báo...">
                <button class="btn btn-sm btn-light border-0"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" width="5%">ID</th>
                            <th width="30%">Tiêu đề</th>
                            <th width="15%">Chuyên mục</th>
                            <th width="15%">Lượt xem</th>
                            <th width="15%">Ngày Đăng</th>
                            <th class="text-end pe-4" width="20%">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if(!empty($news)): ?>
                            <?php foreach($news as $article): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= $article['id'] ?? '?' ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if(!empty($article['thumbnail'])): ?>
                                                <div class="me-3 rounded bg-light overflow-hidden flex-shrink-0" style="width: 60px; height: 40px;">
                                                    <img src="<?= htmlspecialchars($article['thumbnail']) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/60x40?text=No+Img'">
                                                </div>
                                            <?php else: ?>
                                                <div class="me-3 rounded bg-light text-muted d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 40px; font-size: 0.8rem;">
                                                    <i class="fa-regular fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <h6 class="mb-0 fw-bold" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.95rem; line-height: 1.4;">
                                                    <?= htmlspecialchars(isset($article['title']) ? $article['title'] : 'Không có tiêu đề') ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill fw-normal">
                                            <?= htmlspecialchars($article['category'] ?? 'Tin tức') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fa-regular fa-eye me-1 text-muted"></i> <?= number_format($article['view'] ?? 0) ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?= isset($article['pubdate']) ? date('d/m/Y H:i', strtotime($article['pubdate'])) : '---' ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?= _HOST_URL ?>?module=news&action=detail&id=<?= $article['id'] ?? '' ?>" target="_blank" class="btn btn-sm btn-light text-success rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" title="Xem"><i class="fa-solid fa-eye"></i></a>
                                        
                                        <a href="?module=admin&action=news_edit&id=<?= $article['id'] ?? '' ?>" class="btn btn-sm btn-light text-primary rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" title="Sửa"><i class="fa-solid fa-pen"></i></a>
                                        
                                        <a href="?module=admin&action=news&delete_id=<?= $article['id'] ?? '' ?>" class="btn btn-sm btn-light text-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" title="Xoá" onclick="return confirm('Bạn có chắc muốn xoá bài báo này?');"><i class="fa-solid fa-trash-can"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3 text-secondary" style="font-size: 3rem;"><i class="fa-solid fa-newspaper text-opacity-25"></i></div>
                                    <h5 class="fw-bold text-dark text-opacity-50">Chưa có bài báo nào</h5>
                                    <p class="mb-0 small">Bấm vào Thu Thập Dữ Liệu để lấy bài viết mới</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3 p-3 border-top bg-light bg-opacity-50">
                <small class="text-muted">Hiển thị <?= count($news) ?> trên tổng số <?= $totalRecords ?? 0 ?> bài báo</small>
                
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Nút Trước -->
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link border-0 text-muted bg-transparent" href="?module=admin&action=news&page=<?= $page - 1 ?>">Trước</a>
                        </li>
                        
                        <!-- Lặp qua các số trang (rút gọn nếu quá nhiều trang tuỳ chọn) -->
                        <?php 
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        // Luôn hiện trang 1
                        if ($startPage > 1) {
                            echo '<li class="page-item"><a class="page-link rounded-circle border-0 text-dark bg-transparent d-flex align-items-center justify-content-center mx-1" style="width: 30px; height: 30px;" href="?module=admin&action=news&page=1">1</a></li>';
                            if ($startPage > 2) {
                                echo '<li class="page-item disabled"><span class="page-link border-0 bg-transparent text-muted">...</span></li>';
                            }
                        }

                        for ($i = $startPage; $i <= $endPage; $i++): 
                        ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link rounded-circle border-0 <?= ($i == $page) ? 'd-flex align-items-center justify-content-center mx-1 shadow-sm' : 'text-dark bg-transparent d-flex align-items-center justify-content-center mx-1' ?>" style="width: 30px; height: 30px;" href="?module=admin&action=news&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        
                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?>
                                <li class="page-item disabled"><span class="page-link border-0 bg-transparent text-muted">...</span></li>
                            <?php endif; ?>
                            <li class="page-item"><a class="page-link rounded-circle border-0 text-dark bg-transparent d-flex align-items-center justify-content-center mx-1" style="width: 30px; height: 30px;" href="?module=admin&action=news&page=<?= $totalPages ?>"><?= $totalPages ?></a></li>
                        <?php endif; ?>

                        <!-- Nút Sau -->
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link border-0 text-muted bg-transparent" href="?module=admin&action=news&page=<?= $page + 1 ?>">Sau</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php layout('admin_footer'); ?>
