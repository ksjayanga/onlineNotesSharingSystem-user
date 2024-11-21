
<?php 
session_start(); 
include_once('./config/constants.php'); 

if (isset($_POST['submit'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    // Fetch user data from the database
    $select = "SELECT * FROM tbl_accounts WHERE username = '$username' AND password = '$password' ";
    $result = mysqli_query($conn, $select);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);

            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($_SESSION['role'] == 'admin') {
                echo "<script type='text/javascript'> document.location ='./admin/index.php'; </script>";
            } else {
                echo "<script type='text/javascript'> document.location ='index.php'; </script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('Invalid login credentials'); </script>";
        }
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>

    <link rel="stylesheet" href="./css/login.css">
    
</head>
<body class="background">
    <header>
        <h2 class="logo"></h2>
    </header>

    <div class="wrapper">
        <div class="form-box login">
            <h2>Login</h2>
            <form action="" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" id="pass" required>
                    <label>Password</label>
                </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox" onclick="showPass()" > 
                        Show Password</label>
                        <a href="#">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn-login" name="submit">Login</button>
                    <div class="login-register">
                        <p>Don't have an account
                            <a href="signup.php" class="register-link">Register</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

     
    
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
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


