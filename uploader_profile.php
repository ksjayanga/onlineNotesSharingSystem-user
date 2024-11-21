<?php
session_start();
include('partials-front/navbar.php');

?>
<section>
<div class="main-content text-center ">
    <div class="wrapper ">
<?php
// Get the uploader ID (assumed to be passed via URL as ?uploader_id)
$uploader_id = $_GET['uploader_id'];

// Fetch uploader details
$sql = "SELECT * FROM tbl_accounts WHERE id = $uploader_id";
$uploader_res = mysqli_query($conn, $sql);

if ($uploader_res && mysqli_num_rows($uploader_res) == 1) {
    $uploader = mysqli_fetch_assoc($uploader_res);
    $uploader_name = $uploader['full_name'];
    $profile_image = $uploader['image_profile'];
} else {
    echo "<p>Uploader not found.</p>";
    exit; // Stop execution if uploader is not found
}
if ($profile_image == "") {
    echo "<img src='./images/default.png' alt='notes' class='img-curve' width='50px'>";
} else {
    echo "<img src='./images/icon/{$profile_image}' alt='Profile Image' width='40%' class='img-curve'>";
}                       
?>
    </div>
</div>
</section>
 
<section class="menu">
    <div class="container">
        <h2 class="text-center"><?php echo $uploader_name; ?>'s Documents</h2>
            <hr>
        <?php 
        
         
        //SQL Query
        $sql2 = "SELECT * FROM tbl_notes WHERE account_id = $uploader_id";

        //Execute the Query
        $res2 = mysqli_query($conn, $sql2);

        //Count Rows
        $count2 = mysqli_num_rows($res2);

        //CHeck whether food available or not
        if($count2>0)
        {
             
            while($row=mysqli_fetch_assoc($res2))
            {
                //Get all the values
                $note_id = $row['note_id']; 
                $title = $row['title'];
                $image_name = $row['image_name'];
                $image_name = $row['image_name'];
                $likes = $row['likes'];
                ?>

                <div class="float-container menu-box">
                    <div class="menu-img">
                    <a href="note_page.php?note_id=<?php echo $note_id; ?>" class="btn-primary">
                        <?php 
                            //Check whether image available or not
                            if($image_name=="")
                            {
                                //Image not Available
                                echo "<img src='./images/default.png' alt='notes' class='img-responsive img-curve' width='50px'>";
                            }
                            else
                            {
                                //Image Available
                                ?>
                                <img src="./images/icon/<?php echo $image_name; ?>" alt="image" class="img-responsive img-curve">
                                <?php
                            }
                        ?>

                    <div class="menu-desc text-center">
                        <a href="note_page.php?note_id=<?php echo $note_id; ?>"> <h2><?php echo $title; ?></h2></a>
                                   
                            <br>

                        <div class="btn-like-align">    
                            <button id="like-button-<?php echo $note_id; ?>" class="btn btn-like" onclick="toggleLike(<?php echo $note_id; ?>)">

                            <?php
                            // Check if user already liked this note
                            $account_id = $_SESSION['id'];
                            $check_like_sql = "SELECT * FROM tbl_likes WHERE account_id = $account_id AND note_id = $note_id";
                            $check_like_res = mysqli_query($conn, $check_like_sql);
                            if (mysqli_num_rows($check_like_res) > 0) {
                                echo '<i class="fa-solid fa-heart fa-xl"></i>';
                            } else {
                                echo '<i class="fa-regular fa-heart fa-xl"></i>';
                            }
                            ?>
                            
                        </button>
                            <span id="like-count-<?php echo $note_id; ?>"><?php echo $likes; ?></span>
                        </div>   
                    </div>     
                        </a>
                    </div>

                    
                </div>

                <?php
            }
        }
        else
        {
            // Not Available 
            echo "<div class='error'>Food not available.</div>";
        }
        
        ?>

        <div class="clearfix"></div>

    </div>
<hr>
<script src="./js/like.js"></script>  
    
</section>



<?php include('partials-front/footer.php'); ?>