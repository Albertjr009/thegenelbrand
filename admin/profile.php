<?php
session_start();
include '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$success_message = '';
$error_message = '';

// Get current admin info
$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT firstname, lastname, username, email FROM admins WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $conn->real_escape_string($_POST['firstname'] ?? '');
    $lastname = $conn->real_escape_string($_POST['lastname'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    
    if (empty($firstname) || empty($lastname)) {
        $error_message = "First name and last name are required.";
    } else {
        $stmt = $conn->prepare("UPDATE admins SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $firstname, $lastname, $email, $admin_id);
        
        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            $admin['firstname'] = $firstname;
            $admin['lastname'] = $lastname;
            $admin['email'] = $email;
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-2">Manage your admin account information</p>
        </div>
        
        <?php if (!empty($success_message)): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-700 font-medium"><?php echo $success_message; ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p class="text-red-700 font-medium"><?php echo $error_message; ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-800 font-semibold mb-2" for="firstname">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>First Name
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           id="firstname"
                           name="firstname"
                           type="text"
                           value="<?php echo htmlspecialchars($admin['firstname']); ?>"
                           placeholder="Enter first name"
                           required>
                </div>
                
                <div>
                    <label class="block text-gray-800 font-semibold mb-2" for="lastname">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>Last Name
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           id="lastname"
                           name="lastname"
                           type="text"
                           value="<?php echo htmlspecialchars($admin['lastname']); ?>"
                           placeholder="Enter last name"
                           required>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2" for="username">
                    <i class="fas fa-user-tag mr-2 text-blue-500"></i>Username (read-only)
                </label>
                <input class="w-full px-4 py-3 border border-gray-300 bg-gray-100 rounded-lg text-gray-600 cursor-not-allowed"
                       id="username"
                       type="text"
                       value="<?php echo htmlspecialchars($admin['username']); ?>"
                       disabled>
            </div>
            
            <div class="mb-8">
                <label class="block text-gray-800 font-semibold mb-2" for="email">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>Email Address
                </label>
                <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                       id="email"
                       name="email"
                       type="email"
                       value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>"
                       placeholder="Enter email address">
            </div>
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <button class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center"
                        type="submit">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <div class="flex gap-4 w-full md:w-auto">
                    <a href="change_password.php"
                       class="flex-1 md:flex-none bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-lock mr-2"></i>Change Password
                    </a>
                    <a href="dashboard.php"
                       class="flex-1 md:flex-none text-gray-600 hover:text-gray-900 font-semibold py-3 px-6 text-center transition duration-200">
                        ← Back
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
$conn->close();
?>