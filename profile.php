<?php
error_reporting(E_ALL & ~E_NOTICE);

session_start();

// Zkontrolujte, zda je uživatel přihlášen
if (!isset($_SESSION['username'])) {
    // Pokud uživatel není přihlášen, přesměrujte jej na přihlašovací stránku
    header("Location: login.php");
    exit();
}

require_once 'include/dbConnection.php';
require_once 'include/UserManager.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();
$userManager = new UserManager($db);

// Načtěte informace o uživateli z databáze
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Profile - Rap Novinky</title>
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
    <?php
        $navPath = __DIR__ . '/include/navigation.php';
        if (file_exists($navPath) && is_readable($navPath)) {
            include $navPath;
        } else {
            echo 'Navigace není dostupná.';
        }
    ?>
    <header class="masthead" style="background: linear-gradient(180deg, black, #ca6928);">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="page-heading">
                        <h1>Profile</h1>
                        <span class="subheading">Your personal information</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <h2>Your Profile</h2>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

                    <h2>Your Posts</h2>
                    <a href="create_post.php" class="btn btn-primary">Create New Post</a>
                    <hr>
                    <?php
                    // Zobrazit příspěvky uživatele
                    $query = "SELECT post_id, title, post_date FROM posts WHERE user_id=? ORDER BY post_date DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($post = $result->fetch_assoc()) {
                            echo '<div class="post-preview">';
                            echo '<a href="post.php?id=' . $post['post_id'] . '">';
                            echo '<h3 class="post-title">' . htmlspecialchars($post['title']) . '</h3>';
                            echo '<p class="post-meta">Posted on ' . $post['post_date'] . '</p>';
                            echo '</a>';
                            echo '</div>';
                            echo '<hr>';
                        }
                    } else {
                        echo '<p>You have no posts.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer-->
    <footer class="border-top">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <ul class="list-inline text-center">
                        <li class="list-inline-item">
                            <a href="#!">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <div class="small text-center text-muted fst-italic">Copyright &copy; Rap Novinky 2024</div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
