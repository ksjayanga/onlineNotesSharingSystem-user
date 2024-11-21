<?php 
session_start();
include_once('partials-front/navbar.php'); 

?>
<?php

error_reporting(0);
include('../config/constants.php');
if (strlen($_SESSION['id']== 0)) {
header('location:./login.php');
} else{

}

?>

<?php
// Get user ID from session

$id = $_SESSION['id'];
$sql = "SELECT * FROM tbl_accounts WHERE id = '$id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
$user = $result->fetch_assoc();
$username = htmlspecialchars($user['username']);
$profile_image = !empty($user['image_profile']) ? "./images/icon/{$user['image_profile']}" : "../images/default-profile.png";
} else {
echo "No user found.";
}
?>

<!-- Profile Image and Greeting -->

<div style="text-align: right; margin: 20px;">
    <img src="<?php echo $profile_image; ?>" alt="Profile Image " class="profile-image">
    <p style="align-content:center"><?php echo $username; ?></p>
</div>



<br><br>
<h2 class="text-center"><font color=gray font-family=arial>Student Notes Sharing System</font></h2>
<br><br>

       
        <!-- Main Content Setion Ends -->

        <section class="search text-center">
    <div class="container">
        
        <form action="search.php" method="POST">
        
            <input type="search" name="search" placeholder="Search Notes.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">  
        </form>
        
    </div>
</section>
<!-- search Section Ends Here -->


<!-- Categories Section Starts Here -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Explore Notes</h2>

        <?php 
            // SQL Query to Display Categories from Database
            $sql = "SELECT * FROM tbl_subjects WHERE active='Yes' LIMIT 6";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if($count > 0) {
                // Categories Available
                while($row = mysqli_fetch_assoc($res)) {
                    // Get the Values like id, title, image_name
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
                    ?>
                    
                    <!-- Updated Link to Category-Notes Page -->
                        
                        <div class="btn box-3 float-container">
                            <a href="category.php?subject_id=<?php echo $id; ?>">
                            <?php 
                                if ($image_name == "") {
                                    // Display Message if Image not Available
                                    echo "<img src='./images/default.png' alt='notes' class='img-responsive img-curve' width='50px'>";
                                } else {
                                    // Image Available
                                    ?>
                                    <img src="./images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                    <?php
                                }
                            ?>
                            <h3 class="float-text text-white"><?php echo $title; ?></h3>
                            </a>
                        </div>
                   

                    <?php
                }
            } else {
                // Categories not Available
                echo "<div class='error'>Category not Added.</div>";
            }
        ?>
        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->

<!-- Trending Section Starts Here -->
<section class="menu">
    <div class="container">
        <h2 class="text-center">Trending</h2>
            <hr>
        <?php 
        
         
        //SQL Query
        $sql2 = "SELECT * FROM tbl_notes WHERE active='Yes' ORDER BY likes DESC LIMIT 4";

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
                ?>

                <div class="menu-box">
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
                        </a>
                    </div>

                    <div class="menu-desc">
                        <h4 style="text-align: center;"><?php echo $title; ?></h4>
                        <br>
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
<hr><br>
    <p class="text-center">
        <a href="./notes.php">See All Documents</a>
    </p>
    
</section>



<?php include('partials-front/footer.php'); ?>