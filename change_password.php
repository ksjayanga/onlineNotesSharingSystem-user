<?php
session_start();
include('./config/constants.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $id = $_POST['id'];
    $current_password = md5($_POST['current_password']); // Encrypt to match database format
    $new_password = md5($_POST['new_password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Verify the current password
    $sql = "SELECT * FROM tbl_accounts WHERE id = '$id' AND password = '$current_password'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) === 1) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Update the password
            $update_sql = "UPDATE tbl_accounts SET password = '$new_password' WHERE id = '$id'";
            $update_res = mysqli_query($conn, $update_sql);

            if ($update_res) {
                $_SESSION['change-pwd'] = "<div class='success'>Password changed successfully.</div>";
            } else {
                $_SESSION['change-pwd'] = "<div class='error'>Failed to change password.</div>";
            }
        } else {
            $_SESSION['pwd-not-match'] = "<div class='error'>New password and confirm password do not match.</div>";
        }
    } else {
        $_SESSION['user-not-found'] = "<div class='error'>Current password is incorrect.</div>";
    }

    // Redirect back to the profile page
    header('location:myprofile.php');
    exit();
}
?>
