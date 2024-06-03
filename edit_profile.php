<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
require_once 'include/dbConnection.php';

$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Získání ID uživatele z URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Získání informací o uživateli
$query = "SELECT username, email, password FROM users WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Zpracování formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Pokud heslo není prázdné, hashujte ho a aktualizujte
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username=?, email=?, password=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $password, $user_id);
    } else {
        // Aktualizace bez hesla
        $query = "UPDATE users SET username=?, email=? WHERE user_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully.";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to update profile.";
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
    <title>Edit Profile - Rap Novinky</title>
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
                        <h1>Edit Profile</h1>
                        <span class="subheading">Modify your personal information</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="username" name="username" type="text" placeholder="Enter your username..." value="<?php echo htmlspecialchars($user['username']); ?>" required />
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email" placeholder="Enter your email..." value="<?php echo htmlspecialchars($user['email']); ?>" required />
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="password" name="password" type="password" placeholder="Enter a new password if you want to change it" />
                            <label for="password">New Password (leave blank to keep current password)</label>
                        </div>
                        <br />
                        <button class="btn btn-primary text-uppercase" type="submit">Update Profile</button>
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
