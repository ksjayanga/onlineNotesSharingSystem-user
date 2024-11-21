<?php
session_start();
include_once('./config/constants.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['note_id'])) {
    $note_id = $_GET['note_id'];
    $account_id = $_SESSION['id'];

    // Check if the user already liked the note
    $check_like_sql = "SELECT * FROM tbl_likes WHERE account_id = $account_id AND note_id = $note_id";
    $check_like_res = mysqli_query($conn, $check_like_sql);

    if (!$check_like_res) {
        echo "Error checking likes: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($check_like_res) == 0) {
        // User hasn't liked the note yet, so like it
        $like_sql = "INSERT INTO tbl_likes (account_id, note_id) VALUES ($account_id, $note_id)";
        $like_res = mysqli_query($conn, $like_sql);

        if (!$like_res) {
            echo "Error inserting like: " . mysqli_error($conn);
            exit();
        }

        // Increment like count in tbl_notes
        $update_likes_sql = "UPDATE tbl_notes SET likes = likes + 1 WHERE note_id = $note_id";
        $update_likes_res = mysqli_query($conn, $update_likes_sql);

        if (!$update_likes_res) {
            echo "Error updating like count: " . mysqli_error($conn);
            exit();
        }

        echo "Liked";
    } else {
        // User already liked the note, so unlike it
        $unlike_sql = "DELETE FROM tbl_likes WHERE account_id = $account_id AND note_id = $note_id";
        $unlike_res = mysqli_query($conn, $unlike_sql);

        if (!$unlike_res) {
            echo "Error deleting like: " . mysqli_error($conn);
            exit();
        }

        // Decrement like count in tbl_notes
        $update_likes_sql = "UPDATE tbl_notes SET likes = likes - 1 WHERE note_id = $note_id";
        $update_likes_res = mysqli_query($conn, $update_likes_sql);

        if (!$update_likes_res) {
            echo "Error updating like count: " . mysqli_error($conn);
            exit();
        }

        echo "Unliked";
    }
    exit();
}
?>