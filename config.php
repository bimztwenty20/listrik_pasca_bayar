<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "listrik_pasca_bayar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isAdmin() {
    return isset($_SESSION['id_level']) && $_SESSION['id_level'] == 1;
}

function isLoggedIn() {
    return isset($_SESSION['username']);
}

if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("location: login.php");
    exit;
}
?>
