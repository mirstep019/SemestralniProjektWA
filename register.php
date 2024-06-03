<?php
require_once 'include/dbConnection.php';
require_once 'include/UserManager.php'; 

// Inicializace objektu UserManager s připojením k databázi
$db = new Database("localhost", "root", "", "rapnews_database");
$conn = $db->connect();
$userManager = new UserManager($db);

$registrationMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashování hesla

    if ($userManager->registerUser($username, $email, $password)) {
        $registrationMessage = "User has been successfully registered.";
    } else {
        $registrationMessage = "Registration failed.";
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
    <title>Register - Rap Novinky</title>
    <link rel="stylesheet" href="css/styles.css" />
    <style>
        .alert {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php
        $navPath = __DIR__ . '/include/navigation.php';
        if (file_exists($navPath) && is_readable($navPath)) {
            include $navPath;
        } else {
            echo 'Navigation is not available.';
        }
    ?>

    <header class="masthead" style="background-image: url('assets/img/traviskanye.png'); background-position: top;">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="page-heading">
                        <h1>Register</h1>
                        <span class="subheading">Join our community</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php if (!empty($registrationMessage)): ?>
        <div class="alert alert-success text-center"><?php echo $registrationMessage; ?></div>
    <?php endif; ?>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <form method="POST" action="register.php">
                        <div class="form-floating">
                            <input class="form-control" id="username" name="username" type="text" placeholder="Enter your username..." required />
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="email" name="email" type="email" placeholder="Enter your email..." required />
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="password" name="password" type="password" placeholder="Enter your password..." required />
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm your password..." required />
                            <label for="confirmPassword">Confirm Password</label>
                        </div>
                        <br />
                        <button class="btn btn-primary text-uppercase" id="submitButton" type="submit">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer py-4 mt-auto">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="small text-center text-muted fst-italic">Copyright &copy; Rap Novinky 2024</div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
