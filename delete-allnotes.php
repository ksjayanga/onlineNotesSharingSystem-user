<?php 
    session_start();
    //Include COnstants Page
    include('../config/constants.php');

    //echo "Delete Food Page";

    if(isset($_GET['id']) AND isset($_GET['image_name']) AND isset($_GET['doc_name']) ) //Either use '&&' or 'AND'
    {
        //Process to Delete
        //echo "Process to Delete";

        //1.  Get ID and Image NAme
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];
        $doc_name = $_GET['doc_name'];

        //2. Remove the Image if Available
        //CHeck whether the image is available or not and Delete only if available
        if($image_name != "")
        {
            // IT has image and need to remove from folder
            //Get the Image Path
            $path = "../images/icon/".$image_name;

            //REmove Image File from Folder
            $remove = unlink($path);

            //Check whether the image is removed or not
            if($remove==false)
            {
                //Failed to Remove image
                $_SESSION['upload'] = "<div class='error'>Failed to Remove Image File.</div>";
                //REdirect to Manage Food
                header('location:manage-note.php');
                //Stop the Process of Deleting Food
                die();
            }
            //2. Remove the Image if Available
        //CHeck whether the image is available or not and Delete only if available
        if($doc_name != "")
        {
            // IT has image and need to remove from folder
            //Get the Image Path
            $path = "../images/icon/".$doc_name;

            //REmove Image File from Folder
            $remove = unlink($path);

            //Check whether the image is removed or not
            if($remove==false)
            {
                //Failed to Remove image
                $_SESSION['upload'] = "<div class='error'>Failed to Remove Image File.</div>";
                //REdirect to Manage Food
                header('location:manage-note.php');
                //Stop the Process of Deleting Food
                die();
            }
        }

        //3. Delete Food from Database
        $sql = "DELETE FROM tbl_notes WHERE id=$id";
        //Execute the Query
        $res = mysqli_query($conn, $sql);

        //CHeck whether the query executed or not and set the session message respectively
        //4. Redirect to Manage Food with Session Message
        if($res==true)
        {
            //Food Deleted
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
            header('location:manage-note.php');
        }
        else
        {
            //Failed to Delete Food
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Food.</div>";
            header('location:manage-note.php');
        }

        

    }
    else
    {
        //Redirect to Manage Food Page
        //echo "REdirect";
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:manage-note.php');
    }
}
?>