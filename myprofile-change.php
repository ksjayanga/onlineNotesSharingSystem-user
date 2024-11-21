<?php
ob_start(); 
session_start(); 
include_once('partials-front/navbar.php'); 

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo "<script type='text/javascript'> document.location ='./login.php'; </script>";
    exit;
}

// Display status messages
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1>My Profile</h1>
        <hr><br><br>
        
        <?php 
            // Display various session messages
            if (isset($_SESSION['update'])) {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            if (isset($_SESSION['change-pwd'])) {
                echo $_SESSION['change-pwd'];
                unset($_SESSION['change-pwd']);
            }
            if (isset($_SESSION['pwd-not-match'])) {
                echo $_SESSION['pwd-not-match'];
                unset($_SESSION['pwd-not-match']);
            }
            if (isset($_SESSION['user-not-found'])) {
                echo $_SESSION['user-not-found'];
                unset($_SESSION['user-not-found']);
            }

            // Fetch user data for display and editing
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM tbl_accounts WHERE id=$id";
            $res = mysqli_query($conn, $sql);

            if ($res && mysqli_num_rows($res) == 1) {
                $row = mysqli_fetch_assoc($res);
                $full_name = $row['full_name'];
                $email = $row['email'];
                $username = $row['username'];
                $image_profile = $row['image_profile'];
            } else {
                header('location:myprofile.php');
            }
        ?>

        <!-- Profile edit form -->
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                        if ($image_profile == "") {
                            echo "<img src='./images/default.png' alt='notes' class='img-responsive img-curve' width='50px'>";
                        } else {
                            echo "<img src='./images/icon/{$image_profile}' alt='Profile Image' width='50px' class='img-responsive img-curve'>";
                        }
                        ?>
                    </td>
                </tr>
                     
                <tr>
                    <td>Change Profile Image:</td>
                    <td><input type="file" name="new_image"></td>
                </tr>
                           
                <tr>
                    <td>Full Name:</td>
                    <td><input type="text" name="full_name" value="<?php echo $full_name; ?>" required></td>
                </tr>

                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" value="<?php echo $email; ?>" required></td>
                </tr>

                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="username" value="<?php echo $username; ?>" required></td>
                </tr>
                        
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $image_profile; ?>">
                        <br>
                        <button type="submit" name="submit" class="btn btn-secondary">Update Profile</button>
                    </td>
                </tr>
            </table>
        </form> <br><br>
        <!-- Change Password Form -->
        <form action="change_password.php" method="POST" class="password-form">
            <h3>Change Password</h3>
            <table class="tbl-30">
                <tr>
                    <td>Current Password:</td>
                    <td><input type="password" name="current_password" required></td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td><input type="password" name="new_password" required></td>
                </tr>
                <tr>
                    <td>Confirm New Password:</td>
                    <td><input type="password" name="confirm_password" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
                        <button type="submit" name="change_password" class="btn btn-secondary">Change Password</button>
                    </td>
                </tr>
            </table>
        </form>                
    </div>
</div>
<br><br>
<?php include('partials-front/footer.php'); ?>

<?php ob_end_flush(); ?>
