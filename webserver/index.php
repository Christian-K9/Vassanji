<?php

session_start();

include("connection.php");

$message = "";

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Find employee by email
    $sql = "SELECT * FROM employees WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {

        $employee = mysqli_fetch_assoc($result);

        // Check password
        if ($password == $employee['password']) {

            $_SESSION['employeeNumber'] = $employee['employeeNumber'];
            $_SESSION['firstName'] = $employee['firstName'];
            $_SESSION['lastName'] = $employee['lastName'];
            $_SESSION['email'] = $employee['email'];

            header("Location: home.php");
            exit();

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "Email not found.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="style.css">

</head>


<body>


<div id="form">

<h1>Login Form</h1>


<?php

if ($message != "") {

    echo "<p style='color:red;'>$message</p>";

}

?>


<form name="form" action="index.php" method="POST" onsubmit="return isvalid()">


<label>Email:</label>

<input type="email" id="email" name="email">


<br><br>


<label>Password:</label>

<input type="password" id="pass" name="pass">


<br><br>


<input type="submit" id="btn" value="Login" name="submit">


</form>


</div>



<script>

function isvalid(){

    var email = document.form.email.value;
    var pass = document.form.pass.value;


    if(email == "" && pass == ""){

        alert("Email and password fields are empty!");
        return false;

    }

    else if(email == ""){

        alert("Email field is empty!");
        return false;

    }

    else if(pass == ""){

        alert("Password field is empty!");
        return false;

    }


    return true;

}

</script>


</body>

</html>
