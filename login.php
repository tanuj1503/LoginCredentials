<?php 
session_start();
//if already logged in
if(isset($_SESSION['username']))
{
    header("location: welcome.php");
    exit;
}
require_once "config.php";

//declaration of variables
$username = $password = "";
$err = "";

//if request method is post
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password']))){
        $err = "Please enter valid username and password";
    }else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }
    if(empty($err))
    {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

            //TRY TO EXECUTE THE QUERY
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            //this means the password is correct. Allow user to log in
                            session_start();
                            $_SESSION['username'] = $username;
                            $_SESSION['id'] = $id;
                            $_SESSION['loggedin'] = true;

                            header("location: welcome.php");
                        }
                        else{
                            echo "<script>";
                            echo "window.alert('invalid username or password')";
                            echo "</script>";
                        }
                    }
                }
            }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="logins.css"/>
</head>
<body>
<div class="container1">
    <div class="about">
        <h1>Rajeev Classes</h1>
        <p class='para'>Rajeev Classes was founded by Mr. Rajeev Kumar Singh in 2013 to help Engineering/Medical aspirants achieve their dreams. From its very inception, Rajeev Classes aims to provide quality education and in the latest teaching strategies so that our students are ahead in the game.</p>
        <a href="https://www.rajeevclasses.com/about"><p class="readmore">read more...</p></a>
    </div>
</div>

<div class="container2">
    <!-- <img class="logo" src="./images/morale.png" alt=""> -->
    <div class="login-container">
        <form  autocomplete="off" action="" method="POST">
            <input type="text" name="username" placeholder = "USERNAME">
            <input type="password" name="password" placeholder = "PASSWORD">
            <input class="btn" type="submit" name="submit" value="Log In">
        </form>
        <hr></hr>
        <h3><a href="./registration.php">Create Account</a></h3>
    </div>
</div>

</body>
</html>