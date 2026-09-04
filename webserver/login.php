<?php

session_start();

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['pass'];

    $sql = "SELECT * FROM employees WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        if ($password == $row['password']) {

            $_SESSION['employeeNumber'] = $row['employeeNumber'];
            $_SESSION['email'] = $row['email'];

            header("Location: home.php");
            exit();

        } else {

            echo "
                <h2>log in first</h2>

                <form action='index.php' method='get'>
                    <button type='submit'>Go Back</button>
                </form>
            ";

            exit();
        }

    } else {

        echo "
            <h2>log in first</h2>

            <form action='index.php' method='get'>
                <button type='submit'>Go Back</button>
            </form>
        ";

        exit();
    }
}

?>
