<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $type     = $_POST['type'];
    $expiry   = $_POST['expiry_date'];
    $quantity = (int)$_POST['quantity'];
    $user_id  = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO medicines (user_id, name, type, expiry_date, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $type, $expiry, $quantity]);

    $_SESSION['success'] = "Medicine added successfully!";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine - MediSafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-green-50 min-h-screen flex items-center justify-center py-1">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-8">
            <svg width="96" height="96" viewBox="0 0 100 100" class="mx-auto mb-4" fill="none"><rect width="100" height="100" rx="24" fill="#10B981"/><path d="M50 25L56.5 40H73L60.5 50L64 65L50 57L36 65L39.5 50L27 40H43.5L50 25Z" fill="white"/><circle cx="50" cy="50" r="16" fill="#10B981" stroke="white" stroke-width="7"/></svg>
            <h1 class="text-4xl font-bold text-gray-800">Add New Medicine</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <form method="POST">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Medicine Name</label>
                        <input type="text" name="name" required placeholder="e.g. Paracetamol 500mg"
                               class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none text-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Type</label>
                        <select name="type" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none text-lg">
                            <option value="Tablet">Tablet</option>
                            <option value="Capsule">Capsule</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Injection">Injection</option>
                            <option value="Cream">Cream / Ointment</option>
                            <option value="Drops">Drops</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Expiry Date</label>
                        <input type="date" name="expiry_date" required
       class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none text-lg">
                               
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                        <input type="number" name="quantity" required min="1" value="1"
                               class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 outline-none text-lg">
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold py-4 rounded-xl hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition shadow-xl text-lg">
                        ✅ Add Medicine
                    </button>
                    <a href="dashboard.php" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 rounded-xl text-center transition text-lg">
                        ← Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>