<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
require_once 'include/dbConnection.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();

// Zkontrolujte, zda je uživatel přihlášen a má oprávnění
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Získání ID uživatele z URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ověření, zda je uživatel admin nebo vlastní účet
if ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] !== $user_id) {
    header("Location: profile.php");
    exit();
}

// Smazání všech lajků, které uživatel dal
$query = "DELETE FROM likes WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Smazání všech lajků u příspěvků uživatele
$query = "DELETE FROM likes WHERE post_id IN (SELECT post_id FROM posts WHERE user_id=?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Smazání všech příspěvků uživatele
$query = "DELETE FROM posts WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Smazání uživatele z databáze
$query = "DELETE FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Profile deleted successfully.";
    header("Location: profile.php");
    exit();
} else {
    $_SESSION['message'] = "Failed to delete profile.";
    header("Location: profile.php");
    exit();
}
?>
