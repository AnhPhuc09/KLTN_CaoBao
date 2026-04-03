        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-shield-halved"></i>
                <span>AdminPanel</span>
            </div>
            
            <div class="sidebar-menu">
                <?php 
                    $current_module = isset($_GET['module']) ? $_GET['module'] : 'admin';
                    $current_action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
                ?>
                <a href="?module=admin&action=dashboard" class="sidebar-link <?= ($current_action == 'dashboard') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
                
                <h6 class="sidebar-heading px-4 mt-4 mb-2 text-muted text-uppercase" style="font-size: 0.75rem; font-weight: 600;">Quản lý dữ liệu</h6>
                
                <a href="?module=admin&action=users" class="sidebar-link <?= ($current_action == 'users') ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Quản lý Users</span>
                </a>
                
                <a href="?module=admin&action=news" class="sidebar-link <?= ($current_action == 'news') ? 'active' : '' ?>">
                    <i class="fa-solid fa-newspaper"></i>
                    <span>Quản lý Bài báo</span>
                </a>
                
                <a href="?module=admin&action=comments" class="sidebar-link <?= ($current_action == 'comments') ? 'active' : '' ?>">
                    <i class="fa-solid fa-comments"></i>
                    <span>Quản lý Bình luận</span>
                </a>
                
                <h6 class="sidebar-heading px-4 mt-4 mb-2 text-muted text-uppercase" style="font-size: 0.75rem; font-weight: 600;">Hệ thống</h6>
                
                <a href="?module=admin&action=settings" class="sidebar-link <?= ($current_action == 'settings') ? 'active' : '' ?>">
                    <i class="fa-solid fa-gear"></i>
                    <span>Cài đặt</span>
                </a>
                
                <a href="?module=admin&action=logoutqtv" class="sidebar-link text-danger mt-3">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div id="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-bars sidebar-toggle" id="sidebarToggle"></i>
                    <h5 class="mb-0 text-dark fw-bold" id="pageTitle">
                        <?php 
                            if($current_action == 'dashboard') echo "Dashboard";
                            elseif($current_module == 'users') echo "Quản lý Users";
                            elseif($current_module == 'news') echo "Quản lý Bài báo";
                            elseif($current_action == 'comments') echo "Quản lý Bình luận";
                            else echo "Trang quản trị";
                        ?>
                    </h5>
                </div>
                
                <div class="topbar-right">
                    <div class="dropdown">
                        <a href="#" class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <span>Administrator</span>
                            <i class="fa-solid fa-chevron-down ms-2" style="font-size: 0.8rem;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user-pen me-2 text-muted"></i>Hồ sơ</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="?module=admin&action=logoutqtv"><i class="fa-solid fa-power-off me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Page Content Start -->
            <main class="content-area">
