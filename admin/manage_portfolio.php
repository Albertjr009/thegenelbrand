<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Define upload directories (server path for moves, public path for DB)
$upload_dir_server = __DIR__ . '/../uploads/portfolio/';
$upload_dir_public = 'uploads/portfolio/';
if (!is_dir($upload_dir_server)) {
    mkdir($upload_dir_server, 0755, true);
}

// Handle form submission for adding/editing portfolio items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $display_order = (int)$_POST['display_order'];
            $image_path = '';
            
            // Handle image upload
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
                $file = $_FILES['image_file'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                
                if (in_array($file['type'], $allowed_types)) {
                    $filename = 'portfolio_' . time() . '_' . basename($file['name']);
                    $dest_path = $upload_dir_server . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $dest_path)) {
                        // store public-friendly path in DB
                        $image_path = $upload_dir_public . $filename;
                    } else {
                        $error_message = "Error uploading image.";
                    }
                } else {
                    $error_message = "Invalid image format.";
                }
            }
            
            if (!isset($error_message) && !empty($image_path)) {
                $sql = "INSERT INTO portfolio_items (title, description, image_path, display_order) 
                        VALUES ('$title', '$description', '$image_path', $display_order)";
                
                if ($conn->query($sql)) {
                    $success_message = "Portfolio item added successfully!";
                } else {
                    $error_message = "Error adding item: " . $conn->error;
                }
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            
            // Get image path to delete file
            $get_sql = "SELECT image_path FROM portfolio_items WHERE id = $id";
            $get_result = $conn->query($get_sql);
            $item = $get_result->fetch_assoc();
            
            // Attempt to delete old image file (try multiple candidate paths)
            if (!empty($item['image_path'])) {
                $candidates = [
                    $item['image_path'],
                    __DIR__ . '/../' . ltrim($item['image_path'], './'),
                    $upload_dir_server . basename($item['image_path'])
                ];
                foreach ($candidates as $p) {
                    if (file_exists($p)) {
                        @unlink($p);
                    }
                }
            }
            
            $sql = "DELETE FROM portfolio_items WHERE id = $id";
            
            if ($conn->query($sql)) {
                $success_message = "Portfolio item deleted successfully!";
            } else {
                $error_message = "Error deleting item: " . $conn->error;
            }
        } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $display_order = (int)$_POST['display_order'];
            
            // Get current item
            $current_sql = "SELECT image_path FROM portfolio_items WHERE id = $id";
            $current_result = $conn->query($current_sql);
            $current = $current_result->fetch_assoc();
            $image_path = $current['image_path'];
            
            // Handle image upload if provided
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
                $file = $_FILES['image_file'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                
                if (in_array($file['type'], $allowed_types)) {
                    // Delete old image (try multiple candidate paths)
                    if (!empty($current['image_path'])) {
                        $candidates = [
                            $current['image_path'],
                            __DIR__ . '/../' . ltrim($current['image_path'], './'),
                            $upload_dir_server . basename($current['image_path'])
                        ];
                        foreach ($candidates as $p) {
                            if (file_exists($p)) {
                                @unlink($p);
                            }
                        }
                    }
                    
                    $filename = 'portfolio_' . time() . '_' . basename($file['name']);
                    $dest_path = $upload_dir_server . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $dest_path)) {
                        $image_path = $upload_dir_public . $filename;
                    } else {
                        $error_message = "Error uploading image.";
                    }
                }
            }
            
            if (!isset($error_message)) {
                $sql = "UPDATE portfolio_items 
                        SET title = '$title', description = '$description', 
                            image_path = '$image_path', display_order = $display_order 
                        WHERE id = $id";
                
                if ($conn->query($sql)) {
                    $success_message = "Portfolio item updated successfully!";
                } else {
                    $error_message = "Error updating item: " . $conn->error;
                }
            }
        }
    }
}

// Get all portfolio items
$sql = "SELECT * FROM portfolio_items ORDER BY display_order ASC";
$result = $conn->query($sql);
$portfolio_items = [];
while ($row = $result->fetch_assoc()) {
    $portfolio_items[] = $row;
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Manage Portfolio</h1>
            <p class="text-gray-600 mt-2">Add, edit, and organize your portfolio items</p>
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
        
        
            <!-- Add New Item Form -->
            <div class="mb-12 bg-white rounded-lg shadow-lg p-8 border-l-4 border-blue-500">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i>Add New Portfolio Item
                </h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2" for="title">
                                <i class="fas fa-heading mr-2 text-blue-500"></i>Title
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   id="title"
                                   name="title"
                                   type="text"
                                   placeholder="Enter portfolio item title"
                                   required>
                        </div>
                    
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2" for="display_order">
                                <i class="fas fa-sort-numeric-up mr-2 text-blue-500"></i>Display Order
                            </label>
                            <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   id="display_order"
                                   name="display_order"
                                   type="number"
                                   value="<?php echo count($portfolio_items) + 1; ?>"
                                   required>
                        </div>
                    </div>
                
                    <div class="mb-6">
                        <label class="block text-gray-800 font-semibold mb-2" for="description">
                            <i class="fas fa-align-left mr-2 text-blue-500"></i>Description
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-24"
                                  id="description"
                                  name="description"
                                  placeholder="Enter a brief description of this portfolio item"
                                  required></textarea>
                    </div>
                
                    <div class="mb-6">
                        <label class="block text-gray-800 font-semibold mb-2" for="image_file">
                            <i class="fas fa-image mr-2 text-blue-500"></i>Portfolio Image
                        </label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="hidden" onchange="previewPortfolioImage(event, 'addPreview')">
                        <button type="button" onclick="document.getElementById('image_file').click()" 
                                class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-3 px-4 rounded-lg border-2 border-dashed border-blue-300 transition duration-200 flex items-center justify-center cursor-pointer">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Select Image
                        </button>
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Supported formats: JPEG, PNG, GIF, WebP
                        </p>
                        <div id="addPreview" class="mt-4 hidden">
                            <div class="relative inline-block max-w-xs sm:max-w-sm">
                                <img id="addPreviewImg" src="" alt="Preview" class="w-full h-auto rounded-lg border-2 border-blue-300">
                                <button type="button" onclick="clearPortfolioPreview('addPreview')" 
                                        class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 transition duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center"
                            type="submit">
                        <i class="fas fa-plus mr-2"></i>Add Item
                    </button>
                </form>
            </div>
    </div>
    
    <!-- Existing Items List -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            <i class="fas fa-list mr-2 text-blue-500"></i>Existing Portfolio Items
        </h2>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Title</th>
                        <th class="px-6 py-4 text-left font-semibold">Order</th>
                        <th class="px-6 py-4 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($portfolio_items as $item): ?>
                        <tr class="hover:bg-blue-50 transition duration-150" id="item-<?php echo $item['id']; ?>">
                            <td class="px-6 py-4 text-gray-800 font-medium">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    <?php echo $item['display_order']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg mr-2 edit-btn transition duration-200 inline-flex items-center"
                                        onclick="editPortfolioItem(<?php echo $item['id']; ?>)">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </button>
                                <button type="button" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg delete-btn transition duration-200 inline-flex items-center"
                                        onclick="deletePortfolioItem(<?php echo $item['id']; ?>)">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
            </div>
    </div>
    
    <div class="mt-8">
        <a href="dashboard.php"
           class="inline-flex items-center font-bold text-blue-600 hover:text-blue-800 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6 border-b">
            <h2 class="text-2xl font-bold flex items-center">
                <i class="fas fa-edit mr-3"></i>Edit Portfolio Item
            </h2>
        </div>
        
        <form method="POST" id="editForm" class="p-8" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editItemId">
            
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Title</label>
                <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                       name="title"
                       id="editTitle"
                       type="text"
                       placeholder="Enter title"
                       required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Description</label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-24"
                          name="description"
                          id="editDescription"
                          placeholder="Enter description"
                          required></textarea>
            </div>
            
            <!-- Image input replaced by file upload and preview (removed Image URL text field) -->
            <div class="mb-6">
                <div class="mb-6">
                    <label class="block text-gray-800 font-semibold mb-2">Portfolio Image</label>
                        <div id="editCurrentImage" class="mb-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <div class="relative inline-block max-w-xs sm:max-w-sm">
                            <img id="editCurrentImg" src="" alt="Current" class="w-full h-auto rounded-lg border-2 border-gray-300">
                            <button type="button" onclick="deleteEditImage()" 
                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 transition duration-200" title="Delete image">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <input type="file" id="editImageFile" name="image_file" accept="image/*" class="hidden" onchange="previewPortfolioImage(event, 'editPreview')">
                    <button type="button" onclick="document.getElementById('editImageFile').click()" 
                            class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-3 px-4 rounded-lg border-2 border-dashed border-blue-300 transition duration-200 flex items-center justify-center cursor-pointer">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>Select Image
                    </button>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Supported formats: JPEG, PNG, GIF, WebP
                    </p>
                    <div id="editPreview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">New Image:</p>
                        <div class="relative inline-block max-w-xs sm:max-w-sm">
                            <img id="editPreviewImg" src="" alt="Preview" class="w-full h-auto rounded-lg border-2 border-blue-300">
                            <button type="button" onclick="clearPortfolioPreview('editPreview')" 
                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 transition duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="editDeleteImage" name="delete_image" value="0">
                </div>
            
            <div class="mb-8">
                <label class="block text-gray-800 font-semibold mb-2">Display Order</label>
                <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                       name="display_order"
                       id="editDisplayOrder"
                       type="number"
                       required>
            </div>
            
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                        type="submit">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <button class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                        type="button"
                        onclick="closeEditModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
        <div class="bg-red-600 text-white px-8 py-6 rounded-t-2xl border-b">
            <h2 class="text-2xl font-bold flex items-center">
                <i class="fas fa-trash-alt mr-3"></i>Delete Portfolio Item
            </h2>
        </div>
        
        <div class="p-8">
            <p class="text-gray-700 mb-6 flex items-start">
                <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1 flex-shrink-0"></i>
                <span>Are you sure you want to delete this portfolio item? This action cannot be undone.</span>
            </p>
            
            <form method="POST" id="deleteForm">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteItemId">
                
                <div class="flex gap-3">
                    <button class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                            type="submit">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                    <button class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                            type="button"
                            onclick="closeDeleteModal()">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Portfolio item data (populated from PHP)
const portfolioData = {
    <?php foreach ($portfolio_items as $item): ?>
    <?php echo $item['id']; ?>: {
        title: <?php echo json_encode($item['title']); ?>,
        description: <?php echo json_encode($item['description']); ?>,
        image_path: <?php echo json_encode($item['image_path']); ?>,
        display_order: <?php echo $item['display_order']; ?>
    },
    <?php endforeach; ?>
};


function editPortfolioItem(id) {
    const item = portfolioData[id];
    if (!item) return;
    
    document.getElementById('editItemId').value = id;
    document.getElementById('editTitle').value = item.title;
    document.getElementById('editDescription').value = item.description;
    document.getElementById('editDisplayOrder').value = item.display_order;
    
    // Reset file input and preview
    document.getElementById('editImageFile').value = '';
    document.getElementById('editPreview').classList.add('hidden');
    document.getElementById('editDeleteImage').value = '0';
    
    // Show current image if available
    if (item.image_path) {
        document.getElementById('editCurrentImage').classList.remove('hidden');
        document.getElementById('editCurrentImg').src = item.image_path;
    } else {
        document.getElementById('editCurrentImage').classList.add('hidden');
    }
    
    document.getElementById('editModal').classList.remove('hidden');
}

function previewPortfolioImage(event, previewId) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewDiv = document.getElementById(previewId);
        const previewImg = document.getElementById(previewId + 'Img');
        previewImg.src = e.target.result;
        previewDiv.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function clearPortfolioPreview(previewId) {
    document.getElementById(previewId).classList.add('hidden');
    if (previewId === 'addPreview') {
        document.getElementById('image_file').value = '';
    } else if (previewId === 'editPreview') {
        document.getElementById('editImageFile').value = '';
    }
}

function deleteEditImage() {
    if (confirm('Are you sure you want to delete this image?')) {
        document.getElementById('editDeleteImage').value = '1';
        document.getElementById('editCurrentImage').classList.add('hidden');
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deletePortfolioItem(id) {
    document.getElementById('deleteItemId').value = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php
include 'includes/footer.php';
$conn->close();
?>