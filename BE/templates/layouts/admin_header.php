<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        /* Sidebar Styling */
        #sidebar {
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0d6efd;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        .sidebar-brand i { margin-right: 10px; }
        .sidebar-menu { padding-top: 1rem; }
        .sidebar-link {
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            color: #888;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: #0d6efd;
            background-color: #f1f6ff;
            border-right: 4px solid #0d6efd;
        }
        .sidebar-link:hover i, .sidebar-link.active i { color: #0d6efd; }

        /* Main Content Styling */
        #main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        
        /* Topbar Styling */
        .topbar {
            height: 70px;
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .topbar-right {
            display: flex;
            align-items: center;
        }
        .user-dropdown {
            display: flex;
            align-items: center;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }
        .card-body { padding: 1.5rem; }
        
        /* Utilities */
        .stat-card {
            padding: 1.5rem;
            border-radius: 10px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .stat-card-title { font-size: 1rem; opacity: 0.9; margin-bottom: 0.5rem; }
        .stat-card-value { font-size: 2rem; font-weight: 700; margin-bottom: 0; }
        .stat-card-icon {
            position: absolute;
            right: 1.5rem;
            bottom: 1rem;
            font-size: 4rem;
            opacity: 0.2;
        }
        .bg-gradient-primary { background: linear-gradient(45deg, #0d6efd, #0dcaf0); }
        .bg-gradient-success { background: linear-gradient(45deg, #198754, #20c997); }
        .bg-gradient-warning { background: linear-gradient(45deg, #ffc107, #fd7e14); }
        .bg-gradient-danger { background: linear-gradient(45deg, #dc3545, #e83e8c); }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar { margin-left: -260px; }
            #sidebar.show { margin-left: 0; }
            #main-content { margin-left: 0; }
            .sidebar-toggle { display: block !important; }
        }
        .sidebar-toggle {
            display: none;
            font-size: 1.25rem;
            color: #333;
            cursor: pointer;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <div id="wrapper">
