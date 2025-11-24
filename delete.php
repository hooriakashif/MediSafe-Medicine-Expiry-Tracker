<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM medicines WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

$_SESSION['success'] = "Medicine deleted successfully!";
header("Location: dashboard.php");  // ← ONE LINE ONLY! NO LINE BREAK!
exit();
?>