<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/config.php';

$firstname = '';
$lastname = '';

if (!empty($_SESSION['admin_id'])) {
    $stmt = $conn->prepare("SELECT firstname, lastname FROM admins WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();
} elseif (!empty($_SESSION['admin_name'])) {
    $stmt = $conn->prepare("SELECT firstname, lastname FROM admins WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['admin_name']);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();
}

$initial = 'A';
if (!empty($firstname)) {
    $initial = strtoupper(substr($firstname, 0, 1));
} elseif (!empty($_SESSION['admin_name'])) {
    $initial = strtoupper(substr($_SESSION['admin_name'], 0, 1));
}
?>

<?php include 'includes/header.php'; ?>

<!-- Main Dashboard Content -->
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, <?php echo htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="text-gray-600 mt-1">Here's your admin dashboard overview</p>
    </div>

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Portfolio Items -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Portfolio Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        <?php 
                        $result = $conn->query("SELECT COUNT(*) as count FROM portfolio_items");
                        $row = $result->fetch_assoc();
                        echo $row['count'];
                        ?>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Active projects in portfolio</p>
        </div>

        <!-- Card 2: Last Updated -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">About Section</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        <?php 
                        $result = $conn->query("SELECT COUNT(*) as count FROM about_content");
                        $row = $result->fetch_assoc();
                        echo $row['count'] > 0 ? 'Updated' : 'Pending';
                        ?>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-circle text-purple-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Profile information</p>
        </div>

        <!-- Card 3: Storage -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">System Status</p>
                    <p class="text-lg font-bold text-green-600 mt-1">Active</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">All systems operational</p>
        </div>

        <!-- Card 4: Quick Action -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Content Manager</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">Ready</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cogs text-orange-600 text-lg"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">All features available</p>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- About Section Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">About Section</h3>
                        <p class="text-sm text-gray-600 mt-1">Update your profile heading, bio, and image</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-circle text-blue-600"></i>
                    </div>
                </div>
                <a href="edit_about.php" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    <i class="fas fa-edit mr-2"></i>Edit Now
                </a>
            </div>
        </div>

        <!-- Portfolio Section Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Portfolio Items</h3>
                        <p class="text-sm text-gray-600 mt-1">Create, edit, and manage your portfolio projects</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-purple-600"></i>
                    </div>
                </div>
                <a href="manage_portfolio.php" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium text-sm">
                    <i class="fas fa-plus mr-2"></i>Manage Items
                </a>
            </div>
        </div>

        <!-- Settings Section Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Settings</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage your account security and preferences</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cogs text-orange-600"></i>
                    </div>
                </div>
                <a href="change_password.php" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium text-sm">
                    <i class="fas fa-lock mr-2"></i>Secure
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Updates</h3>
        <div class="space-y-4">
            <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-image text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">Content Management System Active</p>
                    <p class="text-sm text-gray-600">Your admin panel is ready to use</p>
                </div>
            </div>
            <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-briefcase text-purple-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">Portfolio Management Ready</p>
                    <p class="text-sm text-gray-600">Add and manage your portfolio items</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">System Status</p>
                    <p class="text-sm text-gray-600">All systems operational and ready</p>
                </div>
            </div>
        </div>
    </div>
</div>

</div><!-- End Page Content -->
</div><!-- End Main Content Area -->

<?php include 'includes/footer.php'; ?>