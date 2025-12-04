<?php
session_start();
include '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle About Content Update
if (isset($_POST['about_submit'])) {
    $heading = $conn->real_escape_string($_POST['heading']);
    $content = $conn->real_escape_string($_POST['content']);
    // Handle about image upload
    $upload_dir_about_server = __DIR__ . '/../uploads/about/';
    $upload_dir_about_public = 'uploads/about/';
    if (!is_dir($upload_dir_about_server)) {
        mkdir($upload_dir_about_server, 0755, true);
    }

    // fetch current about image path
    $current_about = $conn->query("SELECT image_path FROM about_content WHERE id = 1")->fetch_assoc();
    $image_path = $conn->real_escape_string($current_about['image_path']);
    if (isset($_FILES['about_image_file']) && $_FILES['about_image_file']['error'] === 0) {
        $file = $_FILES['about_image_file'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowed_types)) {
            // delete old image if exists (try candidates)
            if (!empty($image_path)) {
                $candidates = [
                    $image_path,
                    __DIR__ . '/../' . ltrim($image_path, './'),
                    $upload_dir_about_server . basename($image_path)
                ];
                foreach ($candidates as $p) {
                    if (file_exists($p)) @unlink($p);
                }
            }
            $filename = 'about_' . time() . '_' . basename($file['name']);
            $dest = $upload_dir_about_server . $filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $image_path = $upload_dir_about_public . $filename;
            } else {
                $about_error = 'Error uploading about image.';
            }
        } else {
            $about_error = 'Invalid about image format.';
        }
    }

    $sql = "UPDATE about_content SET heading = '$heading', content_text = '$content', image_path = '$image_path' WHERE id = 1";
    if ($conn->query($sql)) {
        $about_success = 'About content updated.';
    } else {
        $about_error = 'Error updating about content: ' . $conn->error;
    }
}

// Handle Portfolio Add/Edit/Delete
if (isset($_POST['portfolio_action'])) {
    if ($_POST['portfolio_action'] === 'add') {
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);
        // Handle portfolio image upload
        $upload_dir_portfolio_server = __DIR__ . '/../uploads/portfolio/';
        $upload_dir_portfolio_public = 'uploads/portfolio/';
        if (!is_dir($upload_dir_portfolio_server)) {
            mkdir($upload_dir_portfolio_server, 0755, true);
        }

        $image_path = '';
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
            $file = $_FILES['image_file'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($file['type'], $allowed_types)) {
                $filename = 'portfolio_' . time() . '_' . basename($file['name']);
                $dest = $upload_dir_portfolio_server . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $image_path = $upload_dir_portfolio_public . $filename;
                } else {
                    $portfolio_error = 'Error uploading portfolio image.';
                }
            } else {
                $portfolio_error = 'Invalid portfolio image format.';
            }
        }
        $display_order = (int)$_POST['display_order'];
        $sql = "INSERT INTO portfolio_items (title, description, image_path, display_order) VALUES ('$title', '$description', '$image_path', $display_order)";
        if ($conn->query($sql)) {
            $portfolio_success = 'Portfolio item added.';
        } else {
            $portfolio_error = 'Error adding item: ' . $conn->error;
        }
    } elseif ($_POST['portfolio_action'] === 'edit') {
        $id = (int)$_POST['id'];
        $title = $conn->real_escape_string($_POST['title']);
        $description = $conn->real_escape_string($_POST['description']);
        // Get current item and handle possible image upload
        $current_item_res = $conn->query("SELECT image_path FROM portfolio_items WHERE id = $id");
        $current_item = $current_item_res->fetch_assoc();
        $image_path = $conn->real_escape_string($current_item['image_path']);
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
            $file = $_FILES['image_file'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($file['type'], $allowed_types)) {
                // delete old image (try candidates)
                if (!empty($current_item['image_path'])) {
                    $candidates = [
                        $current_item['image_path'],
                        __DIR__ . '/../' . ltrim($current_item['image_path'], './'),
                        $upload_dir_portfolio_server . basename($current_item['image_path'])
                    ];
                    foreach ($candidates as $p) {
                        if (file_exists($p)) @unlink($p);
                    }
                }
                $filename = 'portfolio_' . time() . '_' . basename($file['name']);
                $dest = $upload_dir_portfolio_server . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $image_path = $upload_dir_portfolio_public . $filename;
                } else {
                    $portfolio_error = 'Error uploading portfolio image.';
                }
            } else {
                $portfolio_error = 'Invalid portfolio image format.';
            }
        }
        $display_order = (int)$_POST['display_order'];
        $sql = "UPDATE portfolio_items SET title = '$title', description = '$description', image_path = '$image_path', display_order = $display_order WHERE id = $id";
        if ($conn->query($sql)) {
            $portfolio_success = 'Portfolio item updated.';
        } else {
            $portfolio_error = 'Error updating item: ' . $conn->error;
        }
    } elseif ($_POST['portfolio_action'] === 'delete') {
        $id = (int)$_POST['id'];
        // Delete image file if exists
        $current_item_res = $conn->query("SELECT image_path FROM portfolio_items WHERE id = $id");
        $current_item = $current_item_res->fetch_assoc();
        if (!empty($current_item['image_path'])) {
            $candidates = [
                $current_item['image_path'],
                __DIR__ . '/../' . ltrim($current_item['image_path'], './'),
                $upload_dir_portfolio_server . basename($current_item['image_path'])
            ];
            foreach ($candidates as $p) {
                if (file_exists($p)) @unlink($p);
            }
        }
        $sql = "DELETE FROM portfolio_items WHERE id = $id";
        if ($conn->query($sql)) {
            $portfolio_success = 'Portfolio item deleted.';
        } else {
            $portfolio_error = 'Error deleting item: ' . $conn->error;
        }
    }
}

// Fetch current about content
$about = $conn->query("SELECT * FROM about_content WHERE id = 1")->fetch_assoc();
// Fetch all portfolio items
$portfolio = $conn->query("SELECT * FROM portfolio_items ORDER BY display_order ASC");

include 'includes/header.php';
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900">Content Management</h1>
            <p class="text-gray-600 mt-2">Manage all website content in one place</p>
        </div>
        
        <!-- About Section -->
        <div class="mb-12 bg-white rounded-lg shadow-lg p-8 border-l-4 border-blue-500">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-user-circle text-blue-500 mr-2"></i>Edit About Section
            </h2>
            <?php if (!empty($about_success)) echo '<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg"><div class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-3"></i><p class="text-green-700 font-medium">'.$about_success.'</p></div></div>'; ?>
            <?php if (!empty($about_error)) echo '<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg"><div class="flex items-center"><i class="fas fa-exclamation-circle text-red-500 mr-3"></i><p class="text-red-700 font-medium">'.$about_error.'</p></div></div>'; ?>
            
            <form method="POST" class="space-y-6" enctype="multipart/form-data">
                <input type="hidden" name="about_submit" value="1">
                
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>Heading
                    </label>
                    <input type="text" name="heading" value="<?php echo htmlspecialchars($about['heading']); ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Enter heading">
                </div>
                
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>Content
                    </label>
                    <textarea name="content" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-32"
                                  placeholder="Enter your bio/content"><?php echo htmlspecialchars($about['content_text']); ?></textarea>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Profile Image</label>
                        <input type="file" name="about_image_file" id="aboutImageFile" accept="image/*" class="hidden" onchange="previewContentImage(this, 'aboutPreview')">
                        <button type="button" onclick="document.getElementById('aboutImageFile').click()" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-2 px-4 rounded border-2 border-dashed">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Select Image
                        </button>
                        <div id="aboutPreview" class="mt-4 <?php echo empty($about['image_path']) ? 'hidden' : ''; ?>">
                            <?php if (!empty($about['image_path'])): ?>
                                <div class="max-w-xs sm:max-w-sm">
                                    <img src="<?php echo htmlspecialchars($about['image_path']); ?>" id="aboutPreviewImg" class="w-full h-auto rounded shadow-md">
                                </div>
                            <?php else: ?>
                                <div class="max-w-xs sm:max-w-sm hidden" id="aboutPreviewImgWrap">
                                    <img id="aboutPreviewImg" src="" class="w-full h-auto rounded shadow-md">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save About</button>
        </form>
    </div>
    <div class="p-6 bg-gray-50 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Manage Portfolio Items</h2>
        <?php if (!empty($portfolio_success)) echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">'.$portfolio_success.'</div>'; ?>
        <?php if (!empty($portfolio_error)) echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 rounded">'.$portfolio_error.'</div>'; ?>
        <form method="POST" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end" enctype="multipart/form-data">
            <input type="hidden" name="portfolio_action" value="add">
            <input type="text" name="title" placeholder="Title" class="border rounded px-3 py-2" required>
            <input type="text" name="description" placeholder="Description" class="border rounded px-3 py-2" required>
            <div>
                <input type="file" name="image_file" id="portfolioAddImage" accept="image/*" class="hidden" onchange="previewContentImage(this, 'portfolioAddPreview')">
                <button type="button" onclick="document.getElementById('portfolioAddImage').click()" class="border rounded px-3 py-2 w-full text-left">Select Image</button>
                <div id="portfolioAddPreview" class="mt-2 hidden"><img id="portfolioAddPreviewImg" class="max-w-xs w-full h-auto rounded"></div>
            </div>
            <input type="number" name="display_order" placeholder="Order" class="border rounded px-3 py-2" min="1" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded col-span-1 md:col-span-4">Add Portfolio Item</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Title</th>
                        <th class="py-2 px-4 border-b">Description</th>
                        <th class="py-2 px-4 border-b">Image</th>
                        <th class="py-2 px-4 border-b">Order</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($item = $portfolio->fetch_assoc()): ?>
                    <tr>
                                <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <td class="py-2 px-4 border-b"><input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" class="border rounded px-2 py-1 w-full"></td>
                                <td class="py-2 px-4 border-b"><input type="text" name="description" value="<?php echo htmlspecialchars($item['description']); ?>" class="border rounded px-2 py-1 w-full"></td>
                                <td class="py-2 px-4 border-b">
                                    <div class="flex items-center gap-3">
                                        <div class="w-20">
                                            <?php if (!empty($item['image_path'])): ?>
                                                <img id="inlinePreview_img_<?php echo $item['id']; ?>" src="<?php echo htmlspecialchars($item['image_path']); ?>" class="w-full h-auto rounded" alt="">
                                            <?php else: ?>
                                                <div id="inlinePreview_img_<?php echo $item['id']; ?>" class="w-full h-12 bg-gray-100 rounded"></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <input type="file" name="image_file" id="portfolioImage_<?php echo $item['id']; ?>" accept="image/*" class="hidden" onchange="previewInlineImage(this, <?php echo $item['id']; ?>)">
                                            <button type="button" onclick="document.getElementById('portfolioImage_<?php echo $item['id']; ?>').click()" class="bg-blue-100 text-blue-700 px-3 py-1 rounded">Change</button>
                                        </div>
                                    </div>
                                </td>
                        <td class="py-2 px-4 border-b"><input type="number" name="display_order" value="<?php echo $item['display_order']; ?>" class="border rounded px-2 py-1 w-16"></td>
                        <td class="py-2 px-4 border-b flex gap-2">
                            <button type="submit" name="portfolio_action" value="edit" class="bg-blue-500 text-white px-2 py-1 rounded">Save</button>
                            <button type="submit" name="portfolio_action" value="delete" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Delete this item?')">Delete</button>
                        </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; $conn->close(); ?>
<script>
function previewContentImage(input, previewId) {
    const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(previewId + 'Img') || document.getElementById('aboutPreviewImg');
        if (img) img.src = e.target.result;
        const wrapper = document.getElementById(previewId) || document.getElementById('aboutPreview');
        if (wrapper) wrapper.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function previewInlineImage(input, id) {
    const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('inlinePreview_img_' + id);
        if (img) img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}
</script>