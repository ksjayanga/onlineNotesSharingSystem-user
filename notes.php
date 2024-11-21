
<?php 
session_start();

include('partials-front/navbar.php');

?>

<!-- sEARCH Section Starts Here -->
<section class="search text-center">
    <div class="container">
        
        <form action="search.php" method="POST">
            <input type="search" name="search" placeholder="Search Notes.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>

    </div>
</section>
<!-- sEARCH Section Ends Here -->



<!-- MEnu Section Starts Here -->
<section class="menu">
    <div class="container">
        <h2 class="text-center">Notes</h2>
        <hr>
        <?php 

            $id = $_SESSION['id'];
            //Display Foods that are Active
            $sql = "SELECT * FROM tbl_notes WHERE active='Yes' ORDER BY likes DESC";

            //Execute the Query
            $res=mysqli_query($conn, $sql);

            //Count Rows
            $count = mysqli_num_rows($res);

            //CHeck whether the foods are availalable or not
            if($count>0)
            {
                //Foods Available
                while($row=mysqli_fetch_assoc($res))
                {
                    //Get the Values
                    $note_id = $row['note_id']; 
                    $title = $row['title'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
                    $doc_name = $row['doc_name'];
                    $likes = $row['likes'];
                    ?>
                                  
                    <div class="float-container menu-box">
                    <a href="note_page.php?note_id=<?php echo $note_id; ?>">
                        <div>
                       
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
    <script src="./js/like.js">

</script>
</section>

<br><br>
<!-- Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>