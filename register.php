<?php
require 'config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        $_SESSION['success'] = "Account created successfully! Please login.";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $error = "Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediSafe - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="h-full bg-gradient-to-br from-green-50 via-white to-blue-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <svg width="96" height="96" viewBox="0 0 100 100" class="mx-auto mb-4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="100" height="100" rx="24" fill="#10B981"/>
                <path d="M50 25L56.5 40H73L60.5 50L64 65L50 57L36 65L39.5 50L27 40H43.5L50 25Z" fill="white"/>
                <circle cx="50" cy="50" r="16" fill="#10B981" stroke="white" stroke-width="7"/>
            </svg>
            <h1 class="text-4xl font-bold text-gray-800">MediSafe</h1>
            <p class="text-gray-600 mt-2">Join the smart medicine manager ⚕️</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Create Account</h2>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 text-center">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-5">
                    <input type="text" name="name" required placeholder="Full Name" 
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none transition text-lg">
                </div>
                <div class="mb-5">
                    <input type="email" name="email" required placeholder="Email Address" 
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none transition text-lg">
                </div>
                <div class="mb-6">
                    <input type="password" name="password" required placeholder="Password (min 6 characters)" minlength="6"
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none transition text-lg">
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold py-4 rounded-xl hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition duration-200 shadow-xl text-lg">
                    Create My Account
                </button>
            </form>

            <p class="text-center mt-6 text-gray-500">
                Already have an account? 
                <a href="index.php" class="text-blue-600 font-bold hover:underline">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>