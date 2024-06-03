<?php
error_reporting(E_ALL & ~E_NOTICE);

session_start();
require_once 'include/dbConnection.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();

// Získání ID příspěvku z URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Načtení příspěvku z databáze
$query = "SELECT posts.post_id, posts.title, posts.category, posts.subcategory, posts.post_date, posts.image_url, posts.content, users.username, COUNT(likes.like_id) AS like_count
          FROM posts 
          JOIN users ON posts.user_id = users.user_id 
          LEFT JOIN likes ON posts.post_id = likes.post_id
          WHERE posts.post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// Pokud příspěvek nebyl nalezen, přesměrovat na hlavní stránku
if (!$post) {
    header("Location: index.php");
    exit();
}

// Kontrola, zda uživatel už lajkoval příspěvek
$liked_by_user = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $liked_by_user = $result->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlspecialchars($post['title']); ?> - Rap Novinky</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .like-container {
            display: inline-block;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        .like-container .liked {
            color: red;
        }

        .like-container[disabled] {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .like-container[disabled]:hover::after {
            content: 'Only registered users can like posts';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background-color: black;
            color: white;
            padding: 5px;
            border-radius: 5px;
            white-space: nowrap;
            font-size: 12px;
        }
    </style>
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
    <header class="masthead" style="background-image: url('<?php echo htmlspecialchars($post['image_url']); ?>')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                        <h2 class="subheading"><?php echo htmlspecialchars($post['category']); ?> - <?php echo htmlspecialchars($post['subcategory']); ?></h2>
                        <span class="meta">
                            Posted by
                            <a href="#!"><?php echo htmlspecialchars($post['username']); ?></a>
                            on <?php echo date("F j, Y", strtotime($post['post_date'])); ?>
                            <br>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Post Content-->
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div id="like-container" class="like-container <?php echo $liked_by_user ? 'liked' : ''; ?>" <?php echo isset($_SESSION['user_id']) ? '' : 'disabled'; ?>>
                        <i id="like-icon" class="fas fa-heart"></i>
                        <span id="like-count"><?php echo $post['like_count']; ?></span> Likes
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>
            </div>
        </div>
    </article>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const likeContainer = document.getElementById('like-container');
            const likeIcon = document.getElementById('like-icon');
            const likeCount = document.getElementById('like-count');

            if (!likeContainer.hasAttribute('disabled')) {
                likeContainer.addEventListener('click', function() {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "like.php");
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            likeCount.textContent = response.like_count;
                            if (response.liked) {
                                likeIcon.classList.add('liked');
                            } else {
                                likeIcon.classList.remove('liked');
                            }
                        }
                    };
                    xhr.send("post_id=<?php echo $post_id; ?>");
                });
            }
        });
    </script>
</body>

</html>
