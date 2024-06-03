<?php
error_reporting(E_ALL & ~E_NOTICE);

session_start();
require_once 'include/dbConnection.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();

// Zkontrolujte, zda je uživatel přihlášen a má právo smazat příspěvek
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

// Získání ID příspěvku z URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kontrola, zda uživatel může smazat příspěvek (musí být admin nebo autor příspěvku)
$query = "SELECT user_id FROM posts WHERE post_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post || ($post['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) {
    header("Location: index.php");
    exit();
}

// Nejprve smažte všechny související lajky
$query = "DELETE FROM likes WHERE post_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();

// Poté smažte samotný příspěvek
$query = "DELETE FROM posts WHERE post_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();

// Nastavení zprávy a přesměrování zpět na stránku s profilem
$_SESSION['message'] = "The post was successfully deleted.";
header("Location: profile.php");
exit();
?>
