<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="templates/assets/css/list.css" rel="stylesheet">
    <link href="templates/assets/css/search.css" rel="stylesheet">
    <link href="/testcrawl/BE/templates/assets/css/comment.css" rel="stylesheet">

</head>

<body>
    <div class="container">
        <a href="?module=news&action=list" class="btn-back">
            <i class="fa fa-arrow-left"></i> Quay về trang chủ
        </a>

        <div class="news-detail">
            <h2><?= htmlspecialchars($news['title']) ?></h2>
            <?php if (!empty($news['image'])): ?>
            <img src="<?= htmlspecialchars($news['image']) ?>" alt="Ảnh bài báo">
            <?php endif; ?>
            <p><strong>Ngày đăng:</strong> <?= htmlspecialchars($news['pubDate']) ?></p>
            <p><strong>Danh mục:</strong> <?= htmlspecialchars($news['category']) ?></p>
            <p><a href="<?= htmlspecialchars($news['link']) ?>" target="_blank">Xem bài gốc</a></p>
        </div>

        <div class="add-comment">
            <h3>Thêm bình luận</h3>
            <form id="commentAddForm">
                <input type="hidden" name="news_id" value="<?= $news_id ?>">
                <textarea name="content" rows="4" placeholder="Nhập bình luận của bạn..." required></textarea>
                <button type="submit">Gửi bình luận</button>
            </form>
            <script>
            document.getElementById('commentAddForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                fetch('/testcrawl/BE/modules/api/comment_add.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                }).then(res => res.json()).then(res => {
                    if(res.status === 'success') location.reload(); else alert(res.message);
                });
            });
            </script>
        </div>

        <div class="comments">
            <h3>Bình luận</h3>
            <?php if ($comments->num_rows > 0): ?>
            <?php while ($c = $comments->fetch_assoc()): ?>
            <?php
                $baseUrl = '/testcrawl'; // URL gốc của dự án
                $baseDir = $_SERVER['DOCUMENT_ROOT'] . $baseUrl; // Thư mục gốc của dự án
                $defaultAvatar = 'templates/uploads/avatar.jpg'; // Avatar mặc định
            
                $avatarToShow = $defaultAvatar; // Mặc định là avatar default
            
                // 2. Kiểm tra nếu user có avatar riêng
                if (!empty($c['avatar'])) {
                    
                    // 3. Lấy đường dẫn từ DB
                    $userAvatarPath = $c['avatar'];
                    if (strpos($userAvatarPath, $baseUrl . '/') === 0) {
                        $userAvatarPath = substr($userAvatarPath, strlen($baseUrl . '/'));
                    }
                    $userAvatarPath = ltrim($userAvatarPath, '/');
                    $fullFileSystemPath = $baseDir . '/' . $userAvatarPath;
                    
                    if ($userAvatarPath && file_exists($fullFileSystemPath)) {
                        $avatarToShow = $userAvatarPath;
                    }
                }
            
                $avatarPath = $baseUrl . '/' . $avatarToShow;
                
                
            ?>

            <div class="comment-item" data-comment-id="<?= $c['id'] ?>">
                <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar">
                <div class="comment-body">
                    <p><?= htmlspecialchars($c['fullname']) ?></p>

                    <div class="comment-content-display">
                        <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
                        <small><?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['created_at']))) ?></small>
                    </div>

                    <div class="comment-content-edit">
                        <textarea rows="3"><?= htmlspecialchars($c['content']) ?></textarea>
                        <button class="btn-save-comment">Lưu</button>
                        <button class="btn-cancel-comment">Hủy</button>
                    </div>

                </div>

                <?php if ($c['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')): ?>
                <div class="comment-actions">
                    <button class="btn-edit-comment">Sửa</button>

                    <button class="btn-delete-comment" data-comment-id="<?= $c['id'] ?>">Xóa</button>
                </div>
                <?php endif; ?>

            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p>Chưa có bình luận nào.</p>
            <?php endif; ?>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Khi nhấn "Sửa"
        document.querySelectorAll('.btn-edit-comment').forEach(button => {
            button.addEventListener('click', () => {
                const commentItem = button.closest('.comment-item');
                const displayBox = commentItem.querySelector('.comment-content-display');
                const editBox = commentItem.querySelector('.comment-content-edit');
                const actions = commentItem.querySelector('.comment-actions');
                const textarea = editBox.querySelector('textarea');

                textarea.value = displayBox.querySelector('p').innerText.trim();
                displayBox.style.display = 'none';
                editBox.style.display = 'block';
                if (actions) actions.style.display = 'none';
                textarea.focus();
            });
        });

        // Khi nhấn "Hủy"
        document.querySelectorAll('.btn-cancel-comment').forEach(button => {
            button.addEventListener('click', () => {
                const commentItem = button.closest('.comment-item');
                const displayBox = commentItem.querySelector('.comment-content-display');
                const editBox = commentItem.querySelector('.comment-content-edit');
                const actions = commentItem.querySelector('.comment-actions');

                editBox.style.display = 'none';
                displayBox.style.display = 'block';
                if (actions) actions.style.display = 'flex';
            });
        });

        // Khi nhấn "Lưu"
        document.querySelectorAll('.btn-save-comment').forEach(button => {
            button.addEventListener('click', () => {
                const commentItem = button.closest('.comment-item');
                const commentId = commentItem.dataset.commentId;
                const newContent = commentItem.querySelector('.comment-content-edit textarea')
                    .value.trim();

                if (!newContent) {
                    alert('Nội dung bình luận không được để trống.');
                    return;
                }

                // Tạo hiệu ứng loading nhỏ
                button.disabled = true;
                button.innerText = 'Đang lưu...';

                    fetch('/testcrawl/BE/modules/api/comment_edit.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ comment_id: commentId, content: newContent })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Cập nhật nội dung hiển thị
                            commentItem.querySelector('.comment-content-display p')
                                .innerHTML = data.content.replace(/\n/g, '<br>');
                            commentItem.querySelector('.comment-content-display small')
                                .innerText = 'Vừa chỉnh sửa';

                            // Ẩn form edit, hiện lại hiển thị
                            commentItem.querySelector('.comment-content-edit').style
                                .display = 'none';
                            commentItem.querySelector('.comment-content-display').style
                                .display = 'block';
                            if (commentItem.querySelector('.comment-actions')) {
                                commentItem.querySelector('.comment-actions').style
                                    .display = 'flex';
                            }
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Không thể lưu bình luận. Vui lòng thử lại.');
                    })
                    .finally(() => {
                        button.disabled = false;
                        button.innerText = 'Lưu';
                    });
            });
        });
        // Khi nhấn nút Xóa
        document.querySelectorAll('.btn-delete-comment').forEach(button => {
            button.addEventListener('click', () => {
                if(!confirm('Bạn có chắc chắn muốn xóa bình luận này?')) return;
                const commentId = button.dataset.commentId;
                fetch('/testcrawl/BE/modules/api/comment_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ comment_id: commentId })
                }).then(r => r.json()).then(res => {
                    if(res.status === 'success') location.reload();
                    else alert(res.message);
                });
            });
        });
    });
    </script>

</body>

</html>
