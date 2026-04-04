<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="templates/assets/css/list.css" rel="stylesheet">
    <link href="templates/assets/css/search.css" rel="stylesheet">
    <link href="/KLTN_CaoBao/BE/templates/assets/css/comment.css" rel="stylesheet">
    <style>
        /* VnExpress-style CSS styling */
        body { background-color: #f4f4f4; margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; }
        .container { max-width: 900px; margin: 20px auto; background: #fff; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-radius: 8px; }
        .btn-back { display: inline-block; color: #b22222; text-decoration: none; font-weight: bold; margin-bottom: 20px; font-size: 14px; }
        .btn-back:hover { text-decoration: underline; }
        
        .article-header { border-bottom: 1px solid #e5e5e5; padding-bottom: 15px; margin-bottom: 20px; }
        .article-category { text-transform: uppercase; color: #9f224e; font-weight: bold; font-size: 13px; margin-bottom: 10px; display: inline-block; }
        .article-title { font-size: 32px; line-height: 1.4; color: #222; margin: 0 0 15px 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-weight: 700; }
        .article-meta { font-size: 14px; color: #757575; display: flex; align-items: center; justify-content: space-between; }
        
        .article-content { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 19px; line-height: 1.7; color: #111; margin-bottom: 30px; letter-spacing: 0.1px; }
        .article-content p { margin: 0 0 20px 0; text-align: justify; }
        .article-content img { max-width: 100%; height: auto; display: block; margin: 0 auto 10px auto; border-radius: 4px; }
        .article-content figure { margin: 0 0 25px 0; text-align: center; }
        .article-content figcaption { font-size: 14px; color: #757575; font-style: italic; background-color: #f7f7f7; padding: 8px; margin-top: 5px; }
        
        .source-link-wrapper { text-align: left; padding: 15px 0; border-top: 1px solid #e5e5e5; margin-top: 20px; }
        .source-link-wrapper a { display: inline-flex; align-items: center; background: #fdfdfd; border: 1px solid #ddd; padding: 10px 20px; color: #004d80; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 4px; transition: all 0.2s; }
        .source-link-wrapper a:hover { background: #004d80; color: #fff; border-color: #004d80; }
        
        /* Comment form styles */
        .add-comment h3, .comments h3 { font-family: Arial, sans-serif; border-bottom: 2px solid #b22222; display: inline-block; padding-bottom: 8px; margin-top: 30px; }
        #commentAddForm textarea { width: 100%; border: 1px solid #dcdcdc; border-radius: 4px; padding: 15px; font-size: 15px; font-family: inherit; margin-bottom: 10px; resize: vertical; outline: none; }
        #commentAddForm textarea:focus { border-color: #b22222; }
        #commentAddForm button { background: #b22222; color: #fff; border: none; padding: 10px 25px; border-radius: 4px; font-size: 15px; cursor: pointer; font-weight: bold; }
        #commentAddForm button:hover { background: #901616; }
    </style>
</head>

<body>
    <div class="container">
        <a href="?module=news&action=list" class="btn-back">
            &larr; Trở về trang nhà
        </a>

        <!-- ARTICLE HEADER -->
        <article>
            <header class="article-header">
                <span class="article-category"><?= htmlspecialchars($news['category'] ?? 'Tin tức') ?></span>
                <h1 class="article-title"><?= htmlspecialchars($news['title']) ?></h1>
                
                <div class="article-meta">
                    <div class="author-time">
                        <strong>Nguồn: <?= htmlspecialchars($news['source'] ?? 'Tổng hợp') ?></strong> &nbsp;|&nbsp; 
                        <span><?= htmlspecialchars(date('l, d/m/Y H:i', strtotime($news['pubDate']))) ?></span>
                    </div>
                </div>
            </header>

            <!-- ARTICLE CONTENT BODY -->
            <div class="article-content">
                <?php if (!empty($news['image'])): ?>
                    <figure>
                        <img src="<?= htmlspecialchars($news['image']) ?>" alt="Ảnh bài báo">
                        <figcaption>Ảnh đại diện bài báo.</figcaption>
                    </figure>
                <?php endif; ?>
                
                <!-- IN RAW HTML CONTENT -->
                <?= $news['content'] ?>
            </div>

            <!-- SOURCE LINK BUTTON -->
            <div class="source-link-wrapper">
                <a href="<?= htmlspecialchars($news['link']) ?>" target="_blank">
                    Đọc bài viết gốc trên <?= htmlspecialchars($news['source'] ?? 'Tòa soạn báo') ?> &rarr;
                </a>
            </div>
        </article>

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
                fetch('/KLTN_CaoBao/BE/modules/api/comment_add.php', {
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
                $baseUrl = '/KLTN_CaoBao'; // URL gốc của dự án
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

                    fetch('/KLTN_CaoBao/BE/modules/api/comment_edit.php', {
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
                fetch('/KLTN_CaoBao/BE/modules/api/comment_delete.php', {
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
