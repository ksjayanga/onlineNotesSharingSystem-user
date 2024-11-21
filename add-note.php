<?php
  
 session_start(); 

 include_once('partials-front/navbar.php'); 
 
 ?>


<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Retrieve and sanitize inputs
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $subject = $_POST['subject'];
    $active = isset($_POST['active']) ? 'Yes' : 'No';
    $account_id = $_SESSION['id']; // Assuming user is logged in and user ID is stored in session

    // Initialize variables for files
    $image_name = '';
    $document_name = '';

    if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else
                {
                    $active = "No"; 
                }

    // Image upload handling
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];

        // Auto rename image to avoid duplication
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Note-Img-".rand(0000, 9999).".".$ext; // e.g., Note-Img-9348.jpg

        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "./images/icon/".$image_name;

        // Upload the image
        $upload = move_uploaded_file($source_path, $destination_path);

        // Check if image is uploaded
        if ($upload == false) {
            $_SESSION['message'] = "<div class='error'>Failed to upload image.</div>";
            header('location:add-note.php');
            exit();
        }
    }

    // Document upload handling
    if (isset($_FILES['doc']['name']) && $_FILES['doc']['name'] != "") {
        $document_name = $_FILES['doc']['name'];

        // Auto rename document to avoid duplication
        $ext = pathinfo($document_name, PATHINFO_EXTENSION);
        $document_name = "Note-Doc-".rand(0000, 9999).".".$ext; // e.g., Note-Doc-9348.pdf

        $source_path = $_FILES['doc']['tmp_name'];
        $destination_path = "./documents/".$document_name;

        // Upload the document
        $upload = move_uploaded_file($source_path, $destination_path);

        // Check if document is uploaded
        if ($upload == false) {
            $_SESSION['message'] = "<div class='error'>Failed to upload document.</div>";
            header('location:add-note.php');
            exit();
        }
    }

    // Insert note into the database
    $sql = "INSERT INTO tbl_notes SET
        title='$title',
        description='$description',
        image_name='$image_name',
        subject_id = $subject,
        doc_name='$document_name',
        active='$active',
        account_id='$account_id'
    ";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($res == true) {
        $_SESSION['message'] = "<div class='success'>Note added successfully.</div>";
        header('location:myprofile.php');
    } else {
        $_SESSION['message'] = "<div class='error'>Failed to add note.</div>";
        header('location:myprofile.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Note</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    

    <div class="main-content">
        <div class="wrapper">
            <h1>Add New Note.</h1>
        <hr>
            <br><br>

            <form action="" method="POST" enctype="multipart/form-data">
                <table class="tbl-30">
                    <tr>
                        <td>Title: </td>
                        <td><input type="text" name="title" placeholder="Title of the Note" required></td>
                    </tr>
                    <tr>
                        <td>Description: </td>
                        <td>
                            <textarea name="description" cols="30" rows="5" placeholder="Description of the Note"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Select Image: </td>
                        <td><input type="file" name="image"></td>
                    </tr>
                    <tr>
                        <td>Select Document: </td>
                        <td><input type="file" name="doc" required></td>
                    </tr>
                    <tr>
                    <td>Subject : </td>
                    <td>
                        <select class="dropdown" name="subject">

                          <option hidden selected>Choose Subject</option>
                            <?php 
                                //Create PHP Code to display subject categories and get all active categories from database
                                
                                $sql = "SELECT * FROM tbl_subjects WHERE active='Yes'";
                                
                                //Executing query
                                $res = mysqli_query($conn, $sql);

                                //Count Rows to check whether we have categories or not
                                $count = mysqli_num_rows($res);

                                //IF count is greater than zero, we have categories else we donot have categories
                                if($count>0)
                                {
                                    //WE have categories
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        //get the details of categories
                                        $id = $row['id'];
                                        $title = $row['title'];

                                        ?>

                                        <option value="<?php echo $id; ?>"><?php echo $title; ?></option>

                                        <?php
                                    }
                                }
                                else
                                {
                                    //WE do not have category
                                    ?>
                                    <option value="0">No Subject Found</option>
                                    <?php
                                }
                            
                            ?>

                        </select>
                    </td>
                </tr>
                    <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes 
                        <input type="radio" name="active" value="No"> No
                    </td>
                    </tr>
                    <br>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="Add Note" class="btn btn-secondary">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php include('partials-front/footer.php'); ?>
</body>
</html>