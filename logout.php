<?php
// Spusť session
session_start();

// Zruš všechny proměnné v session
$_SESSION = array();

// Zruš session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Znič session
session_destroy();

// Přesměruj na domovskou stránku nebo jinou požadovanou stránku po odhlášení
header("Location: index.php"); // Uprav 'index.php' podle potřeby
exit();
?>