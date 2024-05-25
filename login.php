<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start(); // Spusť session pro ukládání informací o přihlášeném uživateli
require_once 'include/UserManager.php'; // Připojení souboru s třídou pro správu uživatelů
require_once 'include/dbConnection.php'; // Připojení k databázi

// Vytvoření instance třídy UserManager s existujícím připojením k databázi
$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();
$userManager = new UserManager($db);

$login_result = ''; // Inicializace proměnné

// Zpracování přihlašovacího formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login_result = $userManager->login($email, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - Rap Novinky</title>
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
    <header class="masthead" style="background-image: url('assets/img/traviskanye.png'); background-position: top;">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="page-heading">
                        <h1>Login</h1>
                        <span class="subheading">Access your account</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php if ($login_result !== true && !empty($login_result)): ?>
        <div style="margin-top: 0px;" class="alert alert-danger"><?php echo $login_result; ?></div>
    <?php endif; ?>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <form method="POST" action="login.php">
                        <div class="form-floating">
                            <input class="form-control" id="email" name="email" type="email" placeholder="Enter your email..." required />
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="password" name="password" type="password" placeholder="Enter your password..." required />
                            <label for="password">Password</label>
                        </div>
                        <br />
                        <button class="btn btn-primary text-uppercase" id="submitButton" type="submit">Login</button>
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
