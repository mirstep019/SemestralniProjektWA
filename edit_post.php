<?php
error_reporting(E_ALL & ~E_NOTICE);

session_start();
require_once 'include/dbConnection.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();

// Získání ID příspěvku z URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Zkontrolujte, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Načtení informací o příspěvku z databáze
$query = "SELECT * FROM posts WHERE post_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// Pokud příspěvek neexistuje nebo uživatel nemá právo ho upravit
if (!$post || ($post['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) {
    header("Location: profile.php");
    exit();
}

// Zpracování formuláře pro úpravu příspěvku
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $image_url = $_POST['image_url'];
    $content = $_POST['content'];

    // Aktualizace příspěvku v databázi
    $query = "UPDATE posts SET title=?, category=?, subcategory=?, image_url=?, content=? WHERE post_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $title, $category, $subcategory, $image_url, $content, $post_id);
    $stmt->execute();

    // Nastavení zprávy a přesměrování zpět na stránku s profilem
    $_SESSION['message'] = "The post was successfully updated.";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Edit Post - Rap Novinky</title>
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
                        <h1>Edit Post</h1>
                        <span class="subheading">Make changes to your post</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <form method="POST" action="edit_post.php?id=<?php echo $post_id; ?>">
                        <div class="form-floating">
                            <input class="form-control" id="title" name="title" type="text" placeholder="Enter the title..." value="<?php echo htmlspecialchars($post['title']); ?>" required />
                            <label for="title">Title</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="category" name="category" type="text" placeholder="Enter the category..." value="<?php echo htmlspecialchars($post['category']); ?>" required />
                            <label for="category">Category</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="subcategory" name="subcategory" type="text" placeholder="Enter the subcategory..." value="<?php echo htmlspecialchars($post['subcategory']); ?>" required />
                            <label for="subcategory">Subcategory</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="image_url" name="image_url" type="text" placeholder="Enter the image URL..." value="<?php echo htmlspecialchars($post['image_url']); ?>" required />
                            <label for="image_url">Image URL</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="content" name="content" placeholder="Enter the content..." required><?php echo htmlspecialchars($post['content']); ?></textarea>
                            <label for="content">Content</label>
                        </div>
                        <br />
                        <button class="btn btn-primary text-uppercase" id="submitButton" type="submit">Update Post</button>
                    </form>
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
