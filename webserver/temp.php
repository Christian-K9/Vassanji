<?php

// =============================
// Authentication check
// =============================
session_start();

if (
    !isset($_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true ||
    !isset($_SESSION['email'])
) {
    header("Location: login.php");
    exit();
}


// Database connection
$host = "localhost";
$dbname = "classicmodels";
$username = "testuser";
$password = "testpassword";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Total payments
$totalPayments = $pdo->query("
    SELECT SUM(amount)
    FROM payments
")->fetchColumn();


// Total sales
$totalSales = $pdo->query("
    SELECT SUM(quantityOrdered * priceEach)
    FROM orderdetails
")->fetchColumn();


// Total orders
$totalOrders = $pdo->query("
    SELECT COUNT(*)
    FROM orders
")->fetchColumn();


// Total customers
$totalCustomers = $pdo->query("
    SELECT COUNT(*)
    FROM customers
")->fetchColumn();


// Amount still owed
$amountOwed = $totalSales - $totalPayments;


// Recent payments
$recentPayments = $pdo->query("
    SELECT
        c.customerName,
        p.paymentDate,
        p.checkNumber,
        p.amount
    FROM payments p
    JOIN customers c
        ON p.customerNumber = c.customerNumber
    ORDER BY p.paymentDate DESC
    LIMIT 10
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>ClassicModels</title>

</head>

<body>

    <h1>ClassicModels</h1>

    <p>
        Logged in as:
        <strong><?= htmlspecialchars($_SESSION['email']) ?></strong>
    </p>

    <!-- Reset Password Button -->
    <form action="reset_password.php" method="POST">
        <button type="submit">
            Reset Password
        </button>
    </form>

    <p>
        <a href="logout.php">Log out</a>
    </p>


    <h2>Summary</h2>

    <p>
        <strong>Total Sales:</strong>
        $<?= number_format($totalSales, 2) ?>
    </p>

    <p>
        <strong>Total Payments:</strong>
        $<?= number_format($totalPayments, 2) ?>
    </p>

    <p>
        <strong>Amount Owed:</strong>
        $<?= number_format($amountOwed, 2) ?>
    </p>

    <p>
        <strong>Total Orders:</strong>
        <?= number_format($totalOrders) ?>
    </p>

    <p>
        <strong>Total Customers:</strong>
        <?= number_format($totalCustomers) ?>
    </p>


    <h2>Recent Payments</h2>

    <table border="1" cellpadding="5">

        <tr>
            <th>Customer</th>
            <th>Payment Date</th>
            <th>Check Number</th>
            <th>Amount</th>
        </tr>

        <?php foreach ($recentPayments as $payment): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($payment['customerName']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($payment['paymentDate']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($payment['checkNumber']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($payment['checkNumber']) ?>
                </td>

                <td>
                    $<?= number_format($payment['amount'], 2) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</body>

</html>
