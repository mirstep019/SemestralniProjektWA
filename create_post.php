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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $category = htmlspecialchars($_POST['category']);
    $subcategory = htmlspecialchars($_POST['subcategory']);
    $image_url = htmlspecialchars($_POST['image_url']);
    $content = htmlspecialchars($_POST['content']);
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO posts (user_id, title, category, subcategory, post_date, image_url, content, likes) VALUES (?, ?, ?, ?, NOW(), ?, ?, 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $user_id, $title, $category, $subcategory, $image_url, $content);

    if ($stmt->execute()) {
        $message = "Post created successfully.";
    } else {
        $message = "Error creating post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Create Post - Rap Novinky</title>
    <link rel="stylesheet" href="css/styles.css" />
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const categorySubcategoryMap = {
                'Music': ['Hip-Hop', 'Rap', 'Trap'],
                'Artists': ['Interviews', 'Profiles', 'New Releases'],
                'Events': ['Concerts', 'Festivals', 'Live Shows'],
            };

            const categorySelect = document.getElementById('category');
            const subcategorySelect = document.getElementById('subcategory');

            categorySelect.addEventListener('change', function() {
                const selectedCategory = this.value;
                const subcategories = categorySubcategoryMap[selectedCategory] || [];
                subcategorySelect.innerHTML = '';

                subcategories.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory;
                    option.textContent = subcategory;
                    subcategorySelect.appendChild(option);
                });
            });

            // Trigger change event to populate subcategories on page load if category is selected
            categorySelect.dispatchEvent(new Event('change'));
        });
    </script>
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
                        <h1>Create New Post</h1>
                        <span class="subheading">Share your latest news and updates</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <?php if (isset($message)): ?>
                        <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="create_post.php">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="title" name="title" type="text" placeholder="Enter the title..." required />
                            <label for="title">Title</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" id="category" name="category" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Music">Music</option>
                                <option value="Artists">Artists</option>
                                <option value="Events">Events</option>
                            </select>
                            <label for="category">Category</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" id="subcategory" name="subcategory">
                                <option value="" disabled selected>Select a subcategory</option>
                                <!-- Subcategories will be populated by JavaScript -->
                            </select>
                            <label for="subcategory">Subcategory</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="image_url" name="image_url" type="text" placeholder="Enter the image URL..." />
                            <label for="image_url">Image URL</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="content" name="content" placeholder="Enter the content..." style="height: 200px" required></textarea>
                            <label for="content">Content</label>
                        </div>
                        <button class="btn btn-primary text-uppercase" type="submit">Create Post</button>
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
