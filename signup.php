<?php
session_start(); 
include_once('./config/constants.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tbl_accounts (full_name, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $username, $hashed_password, $role);

    if ($stmt->execute()) {
        
        $_SESSION['success'] = "<div class='success'>User registered successfully!</div>";

    } else {
        
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/index.js"></script>
</head>
<body class="background">
         
    <div class="wrapper-register">
        <div class="form-box login">
        
        <h2>Signup</h2>

        <?php 

            if(isset($_SESSION['success']))  
            {
                echo $_SESSION['success']; //Display the SEssion Message if SEt
                unset($_SESSION['success']); //Remove Session Message
            }

            if(isset($_SESSION['error']))  
            {
                echo $_SESSION['error']; //Display the SEssion Message if SEt
                unset($_SESSION['error']); //Remove Session Message
            }

         ?>

            <form id="signupForm" method="POST">
                <div class="input-box">
                    
                    <input type="text" name="full_name" required>
                    <label>Name</label>
                </div>
                <div class="input-box">
                    
                    <input type="text" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    
                    <input type="password" name="password" id="pass" required>
                    <label>Password</label>
                </div>
                <input type="hidden" name="role" value="user" required>

                    <div class="remember-forgot">
                        <label><input type="checkbox" onclick="showPass()"> 
                        Show Password</label>
                        
                    </div>
                    <button type="submit" class="btn-login" name="submit">Signup</button>
                    <div class="login-register">
                        <p>Don't have an account
                            <a href="login.php" class="register-link">Login</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function showPass(){
            let pass = document.getElementById('pass');
            if(pass.type == "password"){
                pass.type = "text";
            }else{
                pass.type = "password";
            }
        }
    </script>
</body>
</html>




