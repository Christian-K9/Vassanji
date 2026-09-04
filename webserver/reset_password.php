<?php

session_start();

// User must be logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}


// Database connection
$host = "localhost";
$dbname = "classicmodels";
$username = "testuser";
$password = "testpassword";

try {

    $pdo = new PDO(
        "mysql:host=" . $host .
        ";dbname=" . $dbname .
        ";charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}


// Process password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newPassword = $_POST['new_password'] ?? '';

    if ($newPassword === '') {

        $message = "Please enter a new password.";

    } else {

        $stmt = $pdo->prepare("
            UPDATE employees
            SET password = ?
            WHERE email = ?
        ");

        $stmt->execute([
            $newPassword,
            $_SESSION['email']
        ]);

        $message = "Password successfully changed.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Reset Password</title>

</head>

<body>

    <h1>Reset Password</h1>

    <p>
        Account:
        <strong><?= htmlspecialchars($_SESSION['email']) ?></strong>
    </p>


    <?php if (isset($message)): ?>

        <p>
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>


    <form method="POST" action="reset_password.php">

        <label for="new_password">
            New Password:
        </label>

        <br>

        <input
            type="password"
            id="new_password"
            name="new_password"
            required
        >

        <br><br>

        <button type="submit">
            Change Password
        </button>

    </form>


    <br>

    <a href="home.php">Back to Home</a>

</body>

</html>
