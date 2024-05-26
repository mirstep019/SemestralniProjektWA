<?php
session_start();
require_once 'include/dbConnection.php';

$response = ['liked' => false, 'like_count' => 0];

if (isset($_SESSION['user_id']) && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id']);

    $db = new Database("localhost", "root", "", "rapnews_database");
    $conn = $db->connect();

    // Kontrola, zda uživatel už lajkoval příspěvek
    $query = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Uživatelský like existuje, odebereme ho
        $query = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
    } else {
        // Přidáme nový like
        $query = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $response['liked'] = true;
    }

    // Aktualizace počtu lajků
    $query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['like_count'] = $result->fetch_assoc()['like_count'];
}

echo json_encode($response);
?>
