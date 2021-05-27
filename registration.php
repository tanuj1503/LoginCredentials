<?php 
include('config.php');
$username = $password = $confirm_password = "";
$firstName = $lastName = $phone = $gender = $email = $address = $select = $birthdate = "";
$username_err = $password_err = $confirm_password_err = "";
$firstName_err = $lastName_err = $phone_err = $gender_err = $email_err = $address_err = $select_err = $birthdate_err = "";
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
        // $firstName = $_POST['firstname'];
        // $lastName = $_POST['lastname'];
        // $phone = $_POST['phone'];
        // $gender = $_POST['gender'];
        // $email = $_POST['email'];
        // $address = $_POST['address'];
        // $select = $_POST['helper'];
        // $birthdate = Date('y-m-d', strtotime($_POST['birthdate']));
        
        // $sql_query = "INSERT INTO users(firstname,lastname,phone,gender,email,address,helper,birthdate) VALUES('$firstName','$lastName','$phone','$gender','$email','$address','$select','$birthdate')";
        
        // $result = mysqli_query($connection, $sql_query);

    if(empty(trim($_POST['username'])))
    {
        $username_err = "**please enter valid user name";
    }else{
        $sqlquery = "SELECT id FROM users WHERE username = ?"; //still we don't create a table "USER".
        $stmt = mysqli_prepare($connection, $sqlquery);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            //set the value of $param_username
            $param_username = trim($_POST['username']);
            
            //try to execute the statement
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "already exist";
                }else{
                    $username = trim($_POST['username']);
                }
            }else{
                echo "something went wrong";
            }
        }
    }
    mysqli_stmt_close($stmt);


    //check for password
    if(empty(trim($_POST['password'])))
    {
        $password_err = "** Enter your password";
    }elseif(strlen(trim($_POST['password'])) < 7){
        $password_err = "** atleast 8 character";
    }else{
        $password = trim($_POST['password']);
    }

    //check for first name
    if(empty(trim($_POST['firstname'])))
    {
        $firstName_err = "** Enter your valid firstname";
    }else{
        $firstName = trim($_POST['firstname']);
    }

    //check for last name
    if(empty(trim($_POST['lastname'])))
    {
        $lastName_err = "** Enter your valid lastname";
    }else{
        $lastName = trim($_POST['lastname']);
    }

    //check for phone
    if(empty(trim($_POST['phone'])))
    {
        $phone_err = "** Enter your valid phone";
    }else{
        $phone = trim($_POST['phone']);
    }

    //check for gender
    if(empty(trim($_POST['gender'])))
    {
        $gender_err = "** Select gender";
    }else{
        $gender = trim($_POST['gender']);
    }

    //check for email
    if(empty(trim($_POST['email'])))
    {
        $email_err = "** Enter your valid email";
    }else{
        $email = trim($_POST['email']);
    }

    //check for address
    if(empty(trim($_POST['address'])))
    {
        $address_err = "** Enter your valid address";
    }else{
        $address = trim($_POST['address']);
    }

    //check for select
    if(empty(trim($_POST['helper'])))
    {
        $select_err = "** Enter your valid helper";
    }else{
        $select = trim($_POST['helper']);
    }

    //check for select
    if(empty(trim($_POST['birthdate'])))
    {
        $birthdate_err = "** Enter your D.O.B";
    }else{
        $birthdate = date("Y-m-d", strtotime(trim($_POST['birthdate'])));;
    }










    //check for confirm password field
    if(trim($_POST['password']) != trim($_POST['confirmpassword']))
    {
        echo "<script>";
        echo "window.alert('password should match')";
        echo "</script>";
    }

    //if there were no errors, go ahead and insert into the database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($firstName_err) && empty($lastName_err) && empty($gender_err) && empty($phone_err) && empty($email_err) && empty($address_err) && empty($select_err) && empty($birthdate_err))
    {
        $sql = "INSERT INTO users (firstname,lastname,username,password,birthdate,gender,phone,email,address,helper) VALUES(?,?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($connection, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "ssssssssss",$param_firstName, $param_lastName, $param_username, $param_password, $param_birthdate, $param_gender, $param_phone, $param_email, $param_address, $param_select);
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_gender = $gender;
            $param_phone = $phone;
            $param_email = $email;
            $param_address = $address;
            $param_select = $select;
            $param_birthdate = $birthdate;

            //TRY TO EXECUTE THE QUERY
            if(mysqli_stmt_execute($stmt)){
                // echo "<script>";
                // echo "window.alert('succesfully logged in')";
                // echo "</script>";
                header("location: login.php");
            }else{
                echo "something went wrong cannot redirect";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($connection); 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="registration.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <img src="./images/morale.png" alt="">
    <div class="form-container">
        <div class="signup">
            <h3>Sign Up</h3>
            <p>It's easy and quick</p>
            <a href=""><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <hr>
        <form autocomplete="off" action="" method="POST">
            <input type="text" name="firstname" placeholder="First Name" autocomplete="new-password">
            <input type="text" name="lastname" placeholder="Last Name">
            <div class="column">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="confirmpassword" placeholder="Confirm Password">
                <input type="date" name="birthdate">
            </div>
            <div class="radio-btns">
            <label>
                <input type="radio" name="gender" value="Male">
            Male</label>
            <label>
                <input type="radio" name="gender" value="Female">
            Female</label>
            </div>
            <div class="info">
                <input type="text" name="phone" placeholder="Enter your Mobile Number" autocomplete="new-password">
                <input type="text" name="email" placeholder="Enter your email address" autocomplete="new-password">
                <input type="text" name="address" placeholder="Enter your resident address" autocomplete="new-password">
            </div>
            <select name="helper">
                <option value="" selected>SELECT</option>
                <option value="I want to help">I want to help</option>
                <option value="I need help">I need help</option>
            </select>
            <button class="btn" name="submit" type="submit"><strong>Sign Up</strong></button>
        </form>
        </hr>
    </div>
</body>
</html>