<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$med = $stmt->fetch();

if (!$med) { header("Location: dashboard.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $expiry = $_POST['expiry_date'];
    $quantity = (int)$_POST['quantity'];

    $upd = $pdo->prepare("UPDATE medicines SET name=?, type=?, expiry_date=?, quantity=? WHERE id=?");
    $upd->execute([$name, $type, $expiry, $quantity, $id]);
    $_SESSION['success'] = "Medicine updated!";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medicine - MediSafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-green-50 min-h-screen flex items-center justify-center py-12">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-8">
            <svg width="96" height="96" viewBox="0 0 100 100" class="mx-auto mb-4" fill="none"><rect width="100" height="100" rx="24" fill="#10B981"/><path d="M50 25L56.5 40H73L60.5 50L64 65L50 57L36 65L39.5 50L27 40H43.5L50 25Z" fill="white"/><circle cx="50" cy="50" r="16" fill="#10B981" stroke="white" stroke-width="7"/></svg>
            <h1 class="text-4xl font-bold text-gray-800">Edit Medicine</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <form method="POST">
                <div class="grid md:grid-cols-2 gap-6">
                    <div><input type="text" name="name" value="<?=htmlspecialchars($med['name'])?>" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 outline-none text-lg" placeholder="Medicine Name"></div>
                    <div>
                        <select name="type" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 outline-none text-lg">
                            <?php foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Other'] as $t): ?>
                                <option value="<?=$t?>" <?= $med['type']==$t?'selected':'' ?>><?=$t?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div><input type="date" name="expiry_date" value="<?=$med['expiry_date']?>" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 outline-none text-lg"></div>
                    <div><input type="number" name="quantity" value="<?=$med['quantity']?>" min="1" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 outline-none text-lg"></div>
                </div>
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-4 rounded-xl hover:scale-105 transition shadow-xl text-lg">Update Medicine</button>
                    <a href="dashboard.php" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 rounded-xl text-center transition text-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>