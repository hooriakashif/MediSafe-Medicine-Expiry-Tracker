<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Search & Filter & Sort
$search = trim($_GET['search'] ?? '');
$type_filter = $_GET['type'] ?? 'all';
$sort = $_GET['sort'] ?? 'expiry_asc';

$sql = "SELECT * FROM medicines WHERE user_id = ? AND status = 'active'";
$params = [$user_id];

if ($search !== '') {
    $sql .= " AND name LIKE ?";
    $params[] = "%$search%";
}
if ($type_filter !== 'all') {
    $sql .= " AND type = ?";
    $params[] = $type_filter;
}

$order = $sort === 'expiry_desc' ? "expiry_date DESC" : "expiry_date ASC";
$sql .= " ORDER BY $order";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$medicines = $stmt->fetchAll();

function getStatus($expiry_date) {
    $today = new DateTime();
    $exp = new DateTime($expiry_date);
    $diff = $today->diff($exp)->days;
    if ($exp < $today) return 'expired';
    if ($diff <= 7) return 'expiring';
    return 'safe';
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediSafe - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-green-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <svg width="48" height="48" viewBox="0 0 100 100" class="mr-3" fill="none"><rect width="100" height="100" rx="20" fill="#10B981"/><path d="M50 25L56.5 40H73L60.5 50L64 65L50 57L36 65L39.5 50L27 40H43.5L50 25Z" fill="white"/><circle cx="50" cy="50" r="16" fill="#10B981" stroke="white" stroke-width="7"/></svg>
                    <h1 class="text-2xl font-bold text-gray-800">MediSafe</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <strong><?= htmlspecialchars($user_name) ?></strong></span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-medium transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Success Message -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 text-center font-medium">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-3xl font-bold text-gray-800">My Medicines</h2>
            
            <!-- Search + Filter + Sort -->
            <div class="flex flex-wrap gap-3">
                <input type="text" placeholder="Search medicine..." value="<?=htmlspecialchars($search)?>" 
                       onchange="window.location='dashboard.php?search='+this.value" 
                       class="px-4 py-2 border rounded-lg">
                <select onchange="window.location='dashboard.php?type='+this.value" class="px-4 py-2 border rounded-lg">
                    <option value="all" <?= $type_filter==='all'?'selected':'' ?>>All Types</option>
                    <?php foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Other'] as $t): ?>
                        <option value="<?=$t?>" <?= $type_filter===$t?'selected':'' ?>><?=$t?></option>
                    <?php endforeach; ?>
                </select>
                <select onchange="window.location='dashboard.php?sort='+this.value" class="px-4 py-2 border rounded-lg">
                    <option value="expiry_asc" <?= $sort==='expiry_asc'?'selected':'' ?>>Expiry Soonest First</option>
                    <option value="expiry_desc" <?= $sort==='expiry_desc'?'selected':'' ?>>Expiry Latest First</option>
                </select>
            </div>
        </div>

        <?php if (empty($medicines)): ?>
            <div class="text-center py-20">
                <p class="text-2xl text-gray-600 mb-6">No active medicines found</p>
                <a href="add.php" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-xl text-lg font-bold hover:scale-105 transition shadow-lg">Add Your First Medicine</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($medicines as $m): 
                    $status = getStatus($m['expiry_date']);
                    $bg = $status === 'expired' ? 'bg-red-500' : ($status === 'expiring' ? 'bg-yellow-500' : 'bg-green-500');
                    $days = (new DateTime())->diff(new DateTime($m['expiry_date']))->days;
                    $days_text = $status === 'expired' ? abs($days)." days ago" : "in $days days";
                ?>
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="<?= $bg ?> text-white p-4 text-center font-bold text-lg">
                            <?= $status === 'expired' ? 'EXPIRED' : ($status === 'expiring' ? 'EXPIRING SOON' : 'SAFE') ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($m['name']) ?></h3>
                            <p class="text-gray-600 mb-1">Type: <span class="font-medium"><?= ucfirst($m['type']) ?></span></p>
                            <p class="text-gray-600 mb-1">Quantity: <span class="font-medium"><?= $m['quantity'] ?></span></p>
                            <p class="text-gray-600 mb-4">Expires: <span class="font-bold <?= $status==='expired'?'text-red-600':($status==='expiring'?'text-yellow-600':'text-green-600') ?>">
                                <?= date('d M Y', strtotime($m['expiry_date'])) ?> (<?= $days_text ?>)
                            </span></p>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="edit.php?id=<?= $m['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg font-medium transition">Edit</a>
                                <a href="delete.php?id=<?= $m['id'] ?>" onclick="return confirm('Delete forever?')" class="bg-red-600 hover:bg-red-700 text-white text-center py-2 rounded-lg font-medium transition">Delete</a>
                                <a href="mark_status.php?id=<?= $m['id'] ?>&action=used" onclick="return confirm('Mark as Used?')" class="bg-gray-600 hover:bg-gray-700 text-white text-center py-2 rounded-lg font-medium transition text-sm">Used</a>
                                <a href="mark_status.php?id=<?= $m['id'] ?>&action=discarded" onclick="return confirm('Mark as Discarded?')" class="bg-gray-800 hover:bg-black text-white text-center py-2 rounded-lg font-medium transition text-sm">Discarded</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Floating Add Button -->
        <a href="add.php" class="fixed bottom-8 right-8 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-4xl w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition z-20">
            +
        </a>
    </div>
</body>
</html>