<?php 
session_start();
include('partials-front/navbar.php'); 
?>

<!-- fOOD sEARCH Section Starts Here -->
<!-- sEARCH Section Starts Here -->
<section class="search text-center">
    <div class="container">
        <?php 

            $search = mysqli_real_escape_string($conn, $_POST['search']);
        
        ?>


        <h2>Search Your Notes <a href="#" class="text-white">"<?php echo $search; ?>"</a></h2>

    </div>
</section>
<!-- sEARCH Section Ends Here -->



<!-- MEnu Section Starts Here -->
<section class="menu">
    <div class="container">
        <h2 class="text-center">Menu</h2>

        <?php 

            //SQL Query to Get Notess based on search keyword
            
            $sql = "SELECT * FROM tbl_notes WHERE title LIKE '%$search%'";

            
            $res = mysqli_query($conn, $sql);

            
            $count = mysqli_num_rows($res);

            //Check whether food available of not
            if($count>0)
            {
                //Food Available
                while($row=mysqli_fetch_assoc($res))
                {
                    //Get the details
                    $note_id = $row['note_id'];
                    $title = $row['title'];                  
                    $description = $row['description'];
                    $image_name = $row['image_name'];
                    $doc_name = $row['doc_name'];
                    $likes = $row['likes'];
                    ?>

        <div class="float-container menu-box">
                    <a href="note_page.php?note_id=<?php echo $note_id; ?>">
                        <div class=" ">
                       
                            <?php 
                                //CHeck whether image available or not
                                if($image_name=="")
                                {
                                    //Image not Available 
                                    ?>
                                     <img src="./images/default.png" alt="notes" class="img-responsive img-curve" width="60px">
                                    <?php    
                                }
                                else
                                {
                                    ?>     
                                   
                                    
                                    <img src="./images/icon/<?php echo $image_name; ?>" alt="notes" class="img-responsive img-curve" width="60px"> 
                                    <?php
                                }
                            ?>
                            
                        </div>

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
                </div> 
                </a>            
                    <?php
                }
            }
            else
            {
                //Notes not Available
                echo "<div class='error'>Notes not found.</div>";
            }
        ?>
        <div class="clearfix"></div>

        

    </div>
    <script src="./js/like.js"></script>
</section>
<!-- Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>