<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';

// Check if form is submitted
if (isset($_POST['login'])) {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    // Simple query (you can change "admins" to your table name)
    $sql = "SELECT * FROM admins WHERE username='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Compare passwords (hashed preferred)
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "No account found!";
    }
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Voting System</title>
    <link rel="icon" href="assets/images/genelLogo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="flex items-center gap-3 mb-6">
                <img src="../assets/images/genelLogo.jpg" alt="logo" class="w-10 h-10 rounded object-cover" />
                <h1 class="text-xl font-semibold">Administrator</h1>
            </div>
            <?php if (isset($error))
                echo "<p style='color:red;'>$error</p>"; ?>
            <form action="index.php" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" placeholder="Enter your username" autocomplete="off"
                        required autofocus
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-gray-400 focus:ring-0 px-3 py-2 bg-gray-50" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                        autocomplete="off" required
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-gray-400 focus:ring-0 px-3 py-2 bg-gray-50" />
                </div>

                <div>
                    <button type="submit" name="login"
                        class="w-full bg-gray-900 text-white py-2 rounded-md hover:opacity-95">Login</button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>