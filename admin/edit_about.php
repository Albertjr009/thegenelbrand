<?php
session_start();
include '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $conn->real_escape_string($_POST['heading']);
    $content = $conn->real_escape_string($_POST['content']);
    $image_path = $conn->real_escape_string($_POST['image_path']);
    
    $sql = "UPDATE about_content SET heading = '$heading', content_text = '$content', image_path = '$image_path' WHERE id = 1";
    
    if ($conn->query($sql)) {
        $success_message = "About content updated successfully!";
    } else {
        $error_message = "Error updating content: " . $conn->error;
    }
}

// Get current content
$sql = "SELECT * FROM about_content WHERE id = 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Edit About Content</h1>
    
    <?php if (isset($success_message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="max-w-2xl">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="heading">
                Heading
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="heading"
                   name="heading"
                   type="text"
                   value="<?php echo htmlspecialchars($about['heading']); ?>"
                   required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                Content
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-40"
                      id="content"
                      name="content"
                      required><?php echo htmlspecialchars($about['content_text']); ?></textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image_path">
                Image URL
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="image_path"
                   name="image_path"
                   type="text"
                   value="<?php echo htmlspecialchars($about['image_path']); ?>"
                   required>
        </div>
        
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                Save Changes
            </button>
            <a href="dashboard.php"
               class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Back to Dashboard
            </a>
        </div>
    </form>
</div>

<?php
include 'includes/footer.php';
$conn->close();
?>