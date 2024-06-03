<?php
error_reporting(E_ALL & ~E_NOTICE);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'include/dbConnection.php';
require_once 'include/UserManager.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();
$userManager = new UserManager($db);

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, role FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

function truncate($text, $chars = 100) {
    if (strlen($text) <= $chars) {
        return $text;
    }
    $text = substr($text, 0, $chars);
    if (substr($text, -1) != ' ') {
        $text = substr($text, 0, strrpos($text, ' '));
    }
    return $text . '...';
}
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
    <style>
        .like-container {
            display: inline-block;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            user-select: none;
        }

        .like-container .liked {
            color: red;
        }
    </style>
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
                    $query = "SELECT posts.post_id, posts.title, posts.post_date, posts.content, posts.image_url, posts.user_id, users.username, COUNT(likes.like_id) AS like_count
                              FROM posts
                              JOIN users ON posts.user_id = users.user_id
                              LEFT JOIN likes ON posts.post_id = likes.post_id
                              WHERE posts.user_id = ?
                              GROUP BY posts.post_id
                              ORDER BY posts.post_date DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($post = $result->fetch_assoc()) : ?>
                            <div class="post-preview mb-4">
                                <a href="post.php?id=<?php echo $post['post_id']; ?>">
                                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                                    <h3 class="post-subtitle"><?php echo htmlspecialchars(truncate($post['content'], 150)); ?></h3>
                                </a>
                                <p class="post-meta">
                                    Posted by
                                    <a href="#!"><?php echo htmlspecialchars($post['username']); ?></a>
                                    on <?php echo date("F j, Y", strtotime($post['post_date'])); ?>
                                    <br>
                                    <i class="fas fa-heart mt-4"></i> <?php echo $post['like_count']; ?> Likes
                                    <br>
                                    <a href="edit_post.php?id=<?php echo $post['post_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete_post.php?id=<?php echo $post['post_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                </p>
                            </div>
                            <hr class="my-4" />
                        <?php endwhile;
                    } else {
                        echo '<p>You have no posts.</p>';
                    }

                    if ($_SESSION['role'] === 'admin') {
                        echo '<h2>All Users</h2>';
                        $query = "SELECT user_id, username, email FROM users";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($user = $result->fetch_assoc()) : ?>
                                <div class="post-preview mb-4">
                                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                                    <a href="edit_profile.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete_profile.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                    <hr class="my-4" />
                                </div>
                            <?php endwhile;
                        } else {
                            echo '<p>No users found.</p>';
                        }
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
