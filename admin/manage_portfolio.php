<?php
session_start();
include '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission for adding/editing portfolio items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $image_path = $conn->real_escape_string($_POST['image_path']);
            $display_order = (int)$_POST['display_order'];
            
            $sql = "INSERT INTO portfolio_items (title, description, image_path, display_order) 
                    VALUES ('$title', '$description', '$image_path', $display_order)";
            
            if ($conn->query($sql)) {
                $success_message = "Portfolio item added successfully!";
            } else {
                $error_message = "Error adding item: " . $conn->error;
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
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
            $image_path = $conn->real_escape_string($_POST['image_path']);
            $display_order = (int)$_POST['display_order'];
            
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
    <h1 class="text-3xl font-bold mb-6">Manage Portfolio</h1>
    
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
    
    <!-- Add New Item Form -->
    <div class="mb-8 bg-gray-50 p-6 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Add New Portfolio Item</h2>
        <form method="POST" class="max-w-2xl">
            <input type="hidden" name="action" value="add">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="title"
                       name="title"
                       type="text"
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          id="description"
                          name="description"
                          required></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image_path">
                    Image URL
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="image_path"
                       name="image_path"
                       type="text"
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="display_order">
                    Display Order
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="display_order"
                       name="display_order"
                       type="number"
                       value="<?php echo count($portfolio_items) + 1; ?>"
                       required>
            </div>
            
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                Add Item
            </button>
        </form>
    </div>
    
    <!-- Existing Items List -->
    <h2 class="text-xl font-bold mb-4">Existing Portfolio Items</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($portfolio_items as $item): ?>
            <div class="bg-white shadow rounded-lg p-6" id="item-<?php echo $item['id']; ?>">
                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($item['title']); ?>"
                     class="w-full h-48 object-cover mb-4 rounded">
                     
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Title
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               name="title"
                               type="text"
                               value="<?php echo htmlspecialchars($item['title']); ?>"
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Description
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                  name="description"
                                  required><?php echo htmlspecialchars($item['description']); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Image URL
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               name="image_path"
                               type="text"
                               value="<?php echo htmlspecialchars($item['image_path']); ?>"
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Display Order
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               name="display_order"
                               type="number"
                               value="<?php echo $item['display_order']; ?>"
                               required>
                    </div>
                    
                    <div class="flex justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit">
                            Update
                        </button>
                        
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit"
                                onclick="if(!confirm('Are you sure you want to delete this item?')) return false;
                                        this.form.action.value='delete';">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-6">
        <a href="dashboard.php"
           class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
            Back to Dashboard
        </a>
    </div>
</div>

<?php
include 'includes/footer.php';
$conn->close();
?>