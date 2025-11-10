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
    // fallback: look up by username if admin_id isn't set
    $stmt = $conn->prepare("SELECT firstname, lastname FROM admins WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['admin_name']);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();
}

// compute initial for avatar (fallback to 'A')
$initial = 'A';
if (!empty($firstname)) {
    $initial = strtoupper(substr($firstname, 0, 1));
} elseif (!empty($_SESSION['admin_name'])) {
    $initial = strtoupper(substr($_SESSION['admin_name'], 0, 1));
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Admin | thegenelbrand</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" href="assets/images/genelLogo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="profileBtn"
                            class="flex items-center gap-2 text-sm rounded px-3 py-1 hover:bg-gray-100 focus:outline-none"
                            aria-expanded="false" aria-haspopup="true">
                            <span
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-sm"><?php echo $initial; ?></span>
                            <span class="text-sm text-gray-700"><?php echo htmlspecialchars($firstname ? $firstname : $_SESSION['admin_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="profileDropdown"
                            class="hidden absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1">
                            <a href="profile.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                            <a href="logout.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Content Management -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Content Management</h2>
                <ul class="space-y-4">
                    <li>
                        <a href="edit_about.php" 
                           class="block bg-blue-500 hover:bg-blue-600 text-white rounded p-4 transition duration-200">
                            <span class="font-bold">Edit About Content</span>
                            <p class="text-sm opacity-90 mt-1">Update your bio and profile image</p>
                        </a>
                    </li>
                    <li>
                        <a href="manage_portfolio.php"
                           class="block bg-purple-500 hover:bg-purple-600 text-white rounded p-4 transition duration-200">
                            <span class="font-bold">Manage Portfolio</span>
                            <p class="text-sm opacity-90 mt-1">Add, edit, or remove portfolio items</p>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Admin Tools -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Admin Tools</h2>
                <ul class="space-y-4">
                    <li>
                        <a href="change_password.php"
                           class="block bg-gray-500 hover:bg-gray-600 text-white rounded p-4 transition duration-200">
                            <span class="font-bold">Change Password</span>
                            <p class="text-sm opacity-90 mt-1">Update your admin password</p>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php"
                           class="block bg-red-500 hover:bg-red-600 text-white rounded p-4 transition duration-200">
                            <span class="font-bold">Logout</span>
                            <p class="text-sm opacity-90 mt-1">Sign out of the admin panel</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script src="assets/js/app.js"></script>
</body>

</html>