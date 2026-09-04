<?php

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['pass'];

    $sql = "SELECT * FROM employees WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        if ($password == $row['password']) {

            session_start();

            $_SESSION['employeeNumber'] = $row['employeeNumber'];
            $_SESSION['email'] = $row['email'];

            header("Location: home.php");
            exit();

        } else {
            echo "<script>alert('Incorrect password');</script>";
        }

    } else {
        echo "<script>alert('Email not found');</script>";
    }
}

?>
