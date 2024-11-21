<?php
session_start();
include('./config/constants.php');

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $current_image = $_POST['current_image'];

    // Check if a new image is uploaded
    if (isset($_FILES['new_image']['name']) && $_FILES['new_image']['name'] != "") {
        // Handle file upload (e.g., moving to the correct directory)
        $image_name = $_FILES['new_image']['name'];
        $image_tmp = $_FILES['new_image']['tmp_name'];
        move_uploaded_file($image_tmp, "./images/icon/" . $image_name);
    } else {
        // No new image uploaded, retain current image
        $image_name = $current_image;
    }

    // Update the database with the new information
    $sql = "UPDATE tbl_accounts SET
        full_name = '$full_name',
        email = '$email',
        username = '$username',
        image_profile = '$image_name'
        WHERE id = $id";
    
    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['update'] = "Profile updated successfully.";
    } else {
        $_SESSION['update'] = "Failed to update profile.";
    }

    header('location: myprofile.php');
}
?>
