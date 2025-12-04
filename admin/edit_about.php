<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Define upload directories (server path for moves, public path for DB)
$upload_dir_server = __DIR__ . '/../uploads/about/';
$upload_dir_public = 'uploads/about/';
if (!is_dir($upload_dir_server)) {
    mkdir($upload_dir_server, 0755, true);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $conn->real_escape_string($_POST['heading']);
    $content = $conn->real_escape_string($_POST['content']);
    
    // Get current about content to preserve image if not changing
    $current_sql = "SELECT image_path FROM about_content WHERE id = 1";
    $current_result = $conn->query($current_sql);
    $current = $current_result->fetch_assoc();
    $image_path = $current['image_path'];
    
    // Handle image upload if file is provided
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $file = $_FILES['image_file'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($file['type'], $allowed_types)) {
            // Delete old image if exists (try candidates)
            if (!empty($current['image_path'])) {
                $candidates = [
                    $current['image_path'],
                    __DIR__ . '/../' . ltrim($current['image_path'], './'),
                    $upload_dir_server . basename($current['image_path'])
                ];
                foreach ($candidates as $p) {
                    if (file_exists($p)) @unlink($p);
                }
            }
            
            // Generate unique filename
            $filename = 'about_' . time() . '_' . basename($file['name']);
            $dest = $upload_dir_server . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $image_path = $upload_dir_public . $filename;
            } else {
                $error_message = "Error uploading image. Please try again.";
            }
        } else {
            $error_message = "Invalid image format. Please upload JPG, PNG, GIF, or WebP.";
        }
    }
    
    // Handle image deletion
    if (isset($_POST['delete_image']) && $_POST['delete_image'] === '1') {
        if (!empty($current['image_path'])) {
            $candidates = [
                $current['image_path'],
                __DIR__ . '/../' . ltrim($current['image_path'], './'),
                $upload_dir_server . basename($current['image_path'])
            ];
            foreach ($candidates as $p) {
                if (file_exists($p)) @unlink($p);
            }
        }
        $image_path = '';
    }
    
    if (!isset($error_message)) {
        $sql = "UPDATE about_content SET heading = '$heading', content_text = '$content', image_path = '$image_path' WHERE id = 1";
        
        if ($conn->query($sql)) {
            $success_message = "About content updated successfully!";
        } else {
            $error_message = "Error updating content: " . $conn->error;
        }
    }
}

// Get current content
$sql = "SELECT * FROM about_content WHERE id = 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Edit About Section</h1>
            <p class="text-gray-600 mt-2">Update your profile information and bio</p>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-700 font-medium"><?php echo $success_message; ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p class="text-red-700 font-medium"><?php echo $error_message; ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="bg-white rounded-lg shadow-lg p-8" enctype="multipart/form-data">
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2" for="heading">
                    <i class="fas fa-heading mr-2 text-blue-500"></i>Heading
                </label>
                <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                       id="heading"
                       name="heading"
                       type="text"
                       value="<?php echo htmlspecialchars($about['heading']); ?>"
                       placeholder="Enter your heading"
                       required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2" for="content">
                    <i class="fas fa-pen-fancy mr-2 text-blue-500"></i>Content
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-40"
                          id="content"
                          name="content"
                          placeholder="Enter your bio/description"
                          required><?php echo htmlspecialchars($about['content_text']); ?></textarea>
            </div>
            
            <!-- Profile Image Section -->
            <div class="mb-8">
                <label class="block text-gray-800 font-semibold mb-4">
                    <i class="fas fa-image mr-2 text-blue-500"></i>Profile Image
                </label>
                
                <?php
                    $about_image_server = __DIR__ . '/../' . ltrim($about['image_path'], './');
                ?>
                <?php if (!empty($about['image_path']) && file_exists($about_image_server)): ?>
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-3 font-semibold">Current Image:</p>
                    <div class="max-w-xs sm:max-w-sm mb-4">
                       <img src="<?php echo htmlspecialchars($about['image_path']); ?>" alt="Current profile image" 
                           class="w-full h-auto object-cover rounded-lg shadow-md">
                    </div>
                        <div class="flex gap-3">
                            <button type="button" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center"
                                    onclick="document.getElementById('changeImageBtn').click()">
                                <i class="fas fa-exchange-alt mr-2"></i>Change Image
                            </button>
                            <button type="button" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center"
                                    onclick="deleteImage()">
                                <i class="fas fa-trash mr-2"></i>Delete Image
                            </button>
                        </div>
                        <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
                    </div>
                <?php else: ?>
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <p class="text-gray-600 text-center mb-3">
                            <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                            <br>No image uploaded yet
                        </p>
                    </div>
                <?php endif; ?>
                
                <div class="mb-6">
                    <label class="block text-gray-800 font-semibold mb-3">
                        Select Image from Gallery
                    </label>
                    <div class="relative">
                        <input type="file" 
                               id="image_file" 
                               name="image_file" 
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               class="hidden"
                               onchange="previewImage(this)">
                        <button type="button" 
                                id="changeImageBtn"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center"
                                onclick="document.getElementById('image_file').click()">
                            <i class="fas fa-folder-open mr-2"></i>Select Photo from Gallery
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>Supported formats: JPG, PNG, GIF, WebP (Max 5MB)
                    </p>
                </div>
                
                <!-- Preview for newly selected image -->
                <div id="imagePreview" class="hidden mb-6">
                    <p class="text-sm text-gray-600 mb-2 font-semibold">Preview:</p>
                        <div class="max-w-xs sm:max-w-sm">
                            <img id="previewImg" alt="Preview" class="w-full h-auto object-cover rounded-lg shadow-md mb-3">
                        </div>
                    <button type="button" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm"
                            onclick="clearImagePreview()">
                        <i class="fas fa-times mr-2"></i>Cancel Selection
                    </button>
                </div>
            </div>
            
            <div class="flex items-center justify-between gap-4">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center"
                        type="submit">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="dashboard.php"
                   class="text-gray-600 hover:text-gray-900 font-semibold transition duration-200">
                    ← Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('deleteImageInput').value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImagePreview() {
    document.getElementById('image_file').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('deleteImageInput').value = '0';
}

function deleteImage() {
    if (confirm('Are you sure you want to delete this image?')) {
        document.getElementById('deleteImageInput').value = '1';
        document.querySelector('form').submit();
    }
}
</script>

<?php
include 'includes/footer.php';
$conn->close();
?>