<?php 
session_start();
// Include the configuration file for database connection
include('./config/constants.php');

// Check if the `id`, `image_name`, and `doc_name` are set in the GET request
if (isset($_GET['note_id']) && isset($_GET['image_name']) && isset($_GET['doc_name'])) {
    // Get the ID, image name, and document name from the URL
    $note_id = (int)$_GET['note_id']; // Cast to integer for security
    $image_name = $_GET['image_name'];
    $doc_name = $_GET['doc_name'];

    // 1. Remove the Image if Available
    if ($image_name != "") {
        // Image exists, so delete it from the folder
        $image_path = "./images/food/" . $image_name;
        if (file_exists($image_path)) {
            $remove_image = unlink($image_path);
            if ($remove_image == false) {
                // Failed to remove image
                $_SESSION['remove-failed'] = "<div class='error'>Failed to remove image file.</div>";
                header('location:manage-mynote.php');
                exit();
            }
        }
    }

    // 2. Remove the Document if Available
    if ($doc_name != "") {
        // Document exists, so delete it from the folder
        $doc_path = "./documents/" . $doc_name; // Ensure correct path to documents
        if (file_exists($doc_path)) {
            $remove_doc = unlink($doc_path);
            if ($remove_doc == false) {
                // Failed to remove document
                $_SESSION['remove-failed'] = "<div class='error'>Failed to remove document file.</div>";
                header('location:manage-mynote.php');
                exit();
            }
        }
    }

    // 3. Delete Note from Database
    $sql = "DELETE FROM tbl_notes WHERE note_id=$note_id";
    $res = mysqli_query($conn, $sql);

    if ($res == true) {
        // Note Deleted
        $_SESSION['delete'] = "<div class='success'>Note deleted successfully.</div>";
    } else {
        // Failed to Delete Note
        $_SESSION['delete'] = "<div class='error'>Failed to delete note.</div>";
    }

    // 4. Redirect to Manage Notes
    header('location:manage-mynote.php');
    exit();
} else {
    // Redirect to Manage Notes if `id` is not set
    $_SESSION['unauthorize'] = "<div class='error'>Unauthorized access.</div>";
    header('location:manage-mynote.php');
    exit();
}
?>