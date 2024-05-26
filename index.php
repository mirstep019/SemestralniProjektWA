<?php
error_reporting(E_ALL & ~E_NOTICE);

session_start();
require_once 'include/dbConnection.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();

// Načtení příspěvků z databáze
$query = "SELECT posts.post_id, posts.title, posts.category, posts.subcategory, posts.post_date, posts.image_url, posts.content, posts.likes, users.username, COUNT(likes.like_id) AS like_count
          FROM posts 
          JOIN users ON posts.user_id = users.user_id 
          LEFT JOIN likes ON posts.post_id = likes.post_id
          GROUP BY posts.post_id
          ORDER BY posts.post_date DESC";
$result = $conn->query($query);

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
        <title>Rap Novinky</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <?php
            $navPath = __DIR__ . '/include/navigation.php';
            if (file_exists($navPath) && is_readable($navPath)) {
                include $navPath;
            } else {
                echo 'Navigace není dostupná.';
            }
        ?>        
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('https://i0.wp.com/grungecake.com/wp-content/uploads/2024/03/future-metro-boomin-wwe-dont-trust-you-album-trailer-grungecake-thumbnail.gif?fit=520%2C293&ssl=1'); background-position: center;">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="site-heading">
                            <h1>The Rap News</h1>
                            <span class="subheading">Fresh news about rap from worldwide bloggers.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="text-center post-preview">
                    <h2>Featured Posts</h2>
                    <hr class="my-4" />
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <?php while ($post = $result->fetch_assoc()): ?>
                        <div class="post-preview">
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
                            </p>
                        </div>
                        <hr class="my-4" />
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
