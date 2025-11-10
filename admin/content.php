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
    $image_path = $conn->real_escape_string($_POST['image_path']);
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
        $image_path = $conn->real_escape_string($_POST['image_path']);
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
        $image_path = $conn->real_escape_string($_POST['image_path']);
        $display_order = (int)$_POST['display_order'];
        $sql = "UPDATE portfolio_items SET title = '$title', description = '$description', image_path = '$image_path', display_order = $display_order WHERE id = $id";
        if ($conn->query($sql)) {
            $portfolio_success = 'Portfolio item updated.';
        } else {
            $portfolio_error = 'Error updating item: ' . $conn->error;
        }
    } elseif ($_POST['portfolio_action'] === 'delete') {
        $id = (int)$_POST['id'];
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
    <h1 class="text-3xl font-bold mb-6">Content Management</h1>
    <div class="mb-10 p-6 bg-gray-50 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Edit About Section</h2>
        <?php if (!empty($about_success)) echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">'.$about_success.'</div>'; ?>
        <?php if (!empty($about_error)) echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 rounded">'.$about_error.'</div>'; ?>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="about_submit" value="1">
            <div>
                <label class="block font-semibold mb-1">Heading</label>
                <input type="text" name="heading" value="<?php echo htmlspecialchars($about['heading']); ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Content</label>
                <textarea name="content" class="w-full border rounded px-3 py-2" rows="4"><?php echo htmlspecialchars($about['content_text']); ?></textarea>
            </div>
            <div>
                <label class="block font-semibold mb-1">Image URL</label>
                <input type="text" name="image_path" value="<?php echo htmlspecialchars($about['image_path']); ?>" class="w-full border rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save About</button>
        </form>
    </div>
    <div class="p-6 bg-gray-50 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Manage Portfolio Items</h2>
        <?php if (!empty($portfolio_success)) echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">'.$portfolio_success.'</div>'; ?>
        <?php if (!empty($portfolio_error)) echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 rounded">'.$portfolio_error.'</div>'; ?>
        <form method="POST" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <input type="hidden" name="portfolio_action" value="add">
            <input type="text" name="title" placeholder="Title" class="border rounded px-3 py-2" required>
            <input type="text" name="description" placeholder="Description" class="border rounded px-3 py-2" required>
            <input type="text" name="image_path" placeholder="Image URL" class="border rounded px-3 py-2" required>
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
                        <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <td class="py-2 px-4 border-b"><input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" class="border rounded px-2 py-1 w-full"></td>
                        <td class="py-2 px-4 border-b"><input type="text" name="description" value="<?php echo htmlspecialchars($item['description']); ?>" class="border rounded px-2 py-1 w-full"></td>
                        <td class="py-2 px-4 border-b"><input type="text" name="image_path" value="<?php echo htmlspecialchars($item['image_path']); ?>" class="border rounded px-2 py-1 w-full"></td>
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