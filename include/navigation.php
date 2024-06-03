<?php
session_start(); // Spustíme session pro práci s informacemi o uživateli

// Zjistíme, zda je uživatel přihlášen
if (isset($_SESSION['username'])) {
    $user_display = htmlspecialchars($_SESSION['username']);
    $login_state = true;
} else {
    $user_display = 'Guest';
    $login_state = false;
}
?>

<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="index.php"><img src="assets/img/logo.png" style="height: 50px;" alt="Rap Novinky Logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="news.php">News</a></li>
                <?php if ($login_state): ?>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="profile.php" style="color: #ca6928"><?php echo ($user_display); ?></a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
