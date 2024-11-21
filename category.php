<?php 
session_start();
include('partials-front/navbar.php'); 
?>

<?php 
    // Check whether subject_id is passed or not
    if(isset($_GET['subject_id'])) {
        // Get subject_id from URL
        $subject_id = $_GET['subject_id'];
        
        // Get the Category Title Based on subject_id
        $sql = "SELECT title FROM tbl_subjects WHERE id=$subject_id";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $subject_title = $row['title'];
        } else {
            // Redirect if category not found
            header('location:index.php');
            exit();
        }
    } else {
        // Redirect if category is not passed
        header('location:index.php');
        exit();
    }
?>

<section class="search text-center">
    <div class="container">
        <h2>Notes on <a href="#" class="text-white">"<?php echo htmlspecialchars($subject_title); ?>"</a></h2>
    </div>
</section>

<section class="menu">
    <div class="container">
        <h2 class="text-center">Notes</h2>
        
        <?php 
            // Update query to fetch notes in the selected category
            $sql = "SELECT * FROM tbl_notes WHERE subject_id = $subject_id AND active='Yes' ORDER BY likes DESC";
            $res = mysqli_query($conn, $sql);

            if($res && mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $note_id = $row['note_id']; 
                    $title = $row['title'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
                    $doc_name = $row['doc_name'];
                    $likes = $row['likes'];
                    ?>
                    
                    <div class="menu-box">
                        <div class="menu-img">
                            <?php 
                                if($image_name == "") {
                                    // Display default image if no image is available
                                    echo "<a href='note_page.php?note_id=$note_id'><img src='./images/default.png' alt='notes' class='img-responsive img-curve' width='60px'></a>";
                                } else {
                                    echo "<a href='note_page.php?note_id=$note_id'><img src='./images/icon/$image_name' alt='notes' class='img-responsive img-curve' width='60px'></a>";
                                }
                            ?>
                        </div>

                        <div class="menu-desc text-center">
                            <h2><?php echo htmlspecialchars($title); ?></h2>
                            
                            <div class="btn-like-align">    
                                <button id="like-button-<?php echo $note_id; ?>" class="btn btn-like" onclick="toggleLike(<?php echo $note_id; ?>)">
                                    <?php
                                    // Check if the user has already liked this note
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

                    <?php
                }
            } else {
                // Display message if no notes are available in this category
                echo "<div class='error'>Notes not found in this category.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
    <script src="./js/like.js">
</script>
</section>

<?php include('partials-front/footer.php'); ?>