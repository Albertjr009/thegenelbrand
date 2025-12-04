<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get admin info for the navigation
if (!isset($firstname) || !isset($lastname)) {
    include 'config.php';
    
    $admin_id = $_SESSION['admin_id'];
    $stmt = $conn->prepare("SELECT firstname, lastname FROM admins WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin_data = $result->fetch_assoc();
    $stmt->close();
    
    $firstname = $admin_data['firstname'] ?? '';
    $lastname = $admin_data['lastname'] ?? '';
}

$initial = 'A';
if (!empty($firstname)) {
    $initial = strtoupper(substr($firstname, 0, 1));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | thegenelbrand</title>
    <link rel="icon" href="../assets/images/genelLogo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-white border-r border-gray-200 shadow-sm overflow-y-auto z-40 transition-transform duration-300">
        <div class="p-6">
            <a href="dashboard.php" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    T
                </div>
                <span class="text-xl font-bold text-gray-900">TailAdmin</span>
            </a>
        </div>

        <!-- Main Menu -->
        <nav class="px-4 py-6 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition font-medium">
                <i class="fas fa-th-large w-5"></i>
                <span>Dashboard</span>
            </a>
            <div class="space-y-1">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">CONTENT</p>
                <a href="edit_about.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-user-circle w-5"></i>
                    <span class="text-sm">Edit About</span>
                </a>
                <a href="manage_portfolio.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-images w-5"></i>
                    <span class="text-sm">Portfolio</span>
                </a>
                <a href="content.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-list w-5"></i>
                    <span class="text-sm">All Content</span>
                </a>
            </div>
            <div class="space-y-1 pt-4 border-t border-gray-200">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">SETTINGS</p>
                <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-cog w-5"></i>
                    <span class="text-sm">Settings</span>
                </a>
                <a href="change_password.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-lock w-5"></i>
                    <span class="text-sm">Change Password</span>
                </a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="text-sm">Logout</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen flex flex-col">
        <!-- Top Bar -->
        <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 bg-gray-100 rounded-lg px-4 py-2 max-w-sm">
                        <i class="fas fa-search text-gray-400"></i>
                        <input type="text" placeholder="Search or type command..." class="bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400 w-full">
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition relative">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="border-l border-gray-200 pl-4">
                        <button id="profileMenuBtn" class="flex items-center gap-3 hover:opacity-75 transition">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($firstname . ' ' . $lastname, ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                            <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo $initial; ?>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="flex-1 p-6">
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="fixed bottom-6 right-6 p-4 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition z-40 md:hidden">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <script>
        // Mobile menu toggle
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const profileMenuBtn = document.getElementById('profileMenuBtn');
        let isMobileMenuOpen = false;

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                isMobileMenuOpen = !isMobileMenuOpen;
                if (isMobileMenuOpen) {
                    sidebar.classList.remove('-translate-x-full');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-times text-lg"></i>';
                } else {
                    sidebar.classList.add('-translate-x-full');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-lg"></i>';
                }
            });
        }

        // Close sidebar on mobile when clicking a link
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('-translate-x-full');
                    isMobileMenuOpen = false;
                    if (mobileMenuBtn) {
                        mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-lg"></i>';
                    }
                }
            });
        });

        // Handle window resize (show sidebar on desktop)
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                if (mobileMenuBtn) {
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-lg"></i>';
                }
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Initialize mobile view
        if (window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
        }

        // Highlight active menu item
        const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
        sidebarLinks.forEach(link => {
            const href = link.getAttribute('href').split('/').pop();
            if (href === currentPage) {
                link.classList.add('bg-blue-50', 'text-blue-600');
            }
        });
    </script>