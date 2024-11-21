<?php 
ob_start();
session_start();
include('partials-front/navbar.php'); 

?>

<?php 
if (isset($_GET['note_id'])) {
    $note_id = (int)$_GET['note_id']; // Cast to integer for security

    // SQL Query to Get the Selected Note
    $sql2 = "SELECT * FROM tbl_notes WHERE note_id = $note_id";
    // Execute the Query
    $res2 = mysqli_query($conn, $sql2);

    // Check whether the query executed or not
    if ($res2 == true) {
        // Check whether data is available or not
        $count = mysqli_num_rows($res2);
        // Check if we have note data
        if ($count == 1) {
            // Get the Note Details
            $row2 = mysqli_fetch_assoc($res2);
            $title = $row2['title'];
            $description = $row2['description'];
            $current_image = $row2['image_name'];
            $current_doc = $row2['doc_name'];
            $current_subject = $row2['subject_id'];
            $active = $row2['active'];
        } else {
            // Redirect to Manage Notes with Session Message
            $_SESSION['no-note-found'] = "<div class='error'>Note not found.</div>";
            header('location:manage-mynote.php');
            exit();
        }
    }
} else {
    // Redirect to Manage Notes if `id` is not set
    header('location:manage-mynote.php');
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Note</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php 
                        if ($current_image != "") {
                            // Image Available
                            echo "<img src='./images/icon/$current_image' width='150px'>";
                        } else {
                            // Image Not Available
                            echo "<div class='error'>Image not available.</div>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Select New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Current Document: </td>
                    <td>
                        <?php 
                        if ($current_doc != "") {
                            // Document Available
                            echo "<a href='./documents/$current_doc' target='_blank'>View Current Document</a>";
                        } else {
                            // Document Not Available
                            echo "<div class='error'>Document not available.</div>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Select New Document: </td>
                    <td>
                        <input type="file" name="doc">
                    </td>
                </tr>

                <tr>
                    <td>Subject: </td>
                    <td>
                        <select class="dropdown" name="subject">

                         
                            <?php 
                            // Query to Get Active Categories
                            $sql = "SELECT * FROM tbl_subjects WHERE active='Yes'";
                            // Execute the Query
                            $res = mysqli_query($conn, $sql);
                            // Count Rows
                            $count = mysqli_num_rows($res);

                            // Check whether category available or not
                            if ($count > 0) {
                                // Category Available
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $subject_title = $row['title'];
                                    $subject_id = $row['id'];
                                    ?>
                                    <option <?php if ($current_subject == $subject_id) {echo "selected";} ?> value="<?php echo $subject_id; ?>"><?php echo $subject_title; ?></option>
                                    <?php
                                }
                            } else {
                                // Category Not Available
                                echo "<option value='0'>Category Not Available.</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if ($active == "Yes") {echo "checked";} ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if ($active == "No") {echo "checked";} ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="current_doc" value="<?php echo $current_doc; ?>">
                        <input type="submit" name="submit" value="Update" class="btn btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        if (isset($_POST['submit'])) {
            // Get all the details from the form
            $note_id = $_POST['note_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $current_image = $_POST['current_image'];
            $current_doc = $_POST['current_doc'];
            $subject = $_POST['subject'];
            $active = $_POST['active'];

            // Handling image upload
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                // Image file details
                $image_name = $_FILES['image']['name'];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = "Note-Image-" . rand(0000, 9999) . ".$ext";
                $image_temp_path = $_FILES['image']['tmp_name'];
                $image_dest_path = "./images/icon/" . $image_name;

                // Upload the image
                $upload = move_uploaded_file($image_temp_path, $image_dest_path);

                // Check if upload was successful
                if ($upload == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to upload new image.</div>";
                    header('location:manage-mynote.php');
                    exit();
                }

                // Remove the old image if it exists
                if ($current_image != "" && file_exists("./images/icon/" . $current_image)) {
                    unlink("./images/icon/" . $current_image);
                }
            } else {
                $image_name = $current_image;
            }

            // Handling document upload
            if (isset($_FILES['doc']['name']) && $_FILES['doc']['name'] != "") {
                // Document file details
                $doc_name = $_FILES['doc']['name'];
                $doc_temp_path = $_FILES['doc']['tmp_name'];
                $doc_dest_path = "../documents/" . $doc_name;

                // Upload the document
                $upload = move_uploaded_file($doc_temp_path, $doc_dest_path);

                // Check if upload was successful
                if ($upload == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to upload new document.</div>";
                    header('location:manage-mynote.php');
                    exit();
                }

                // Remove the old document if it exists
                if ($current_doc != "" && file_exists("./documents/" . $current_doc)) {
                    unlink("./documents/" . $current_doc);
                }
            } else {
                $doc_name = $current_doc;
            }

            // Update the Note in Database
            $sql3 = "UPDATE tbl_notes SET 
                title = '$title',
                description = '$description',
                image_name = '$image_name',
                doc_name = '$doc_name',
                subject_id = '$subject',
                active = '$active'
                WHERE note_id=$note_id";

            // Execute the SQL Query
            $res3 = mysqli_query($conn, $sql3);

            // Check whether the query is executed or not 
            if ($res3 == true) {
                $_SESSION['update'] = "<div class='success'>Note updated successfully.</div>";
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to update note.</div>";
            }

            // Redirect to Manage Notes
            header('location:manage-mynote.php');
            
            ob_end_flush();
        }
        ?>

    </div>
</div>

<?php 
// Ensure no output before headers are sent

include_once('partials-front/footer.php'); 
?>