<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? '';

if ($action === 'used' || $action === 'discarded') {
    $stmt = $pdo->prepare("UPDATE medicines SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$action, $id, $_SESSION['user_id']]);
    $_SESSION['success'] = "Medicine marked as $action!";
}

header("Location: dashboard.php");
exit();
?>