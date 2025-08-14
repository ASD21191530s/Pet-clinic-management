<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "pet clinic"); // change DB name if needed
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM login WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_password);
            $stmt->fetch();

            if ($password === $db_password || password_verify($password, $db_password)) {
                $_SESSION['name'] = $username;
                $_SESSION['username'] = true;
                header("Location: tut.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
    echo "<div style='color:red;'>Invalid username or password.</div>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

