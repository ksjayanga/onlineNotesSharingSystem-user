<?php 
session_start();
include_once('partials-front/navbar.php'); 

?>

<body>
<section class="menu">
    <div class="container">
        <h2 class="text-center">Explore</h2>

        <br>
        
        <?php 
            //Create SQL Query to Display CAtegories from Database
            $sql = "SELECT * FROM tbl_subjects WHERE active='Yes'";
            //Execute the Query
            $res = mysqli_query($conn, $sql);
            //Count rows to check whether the category is available or not
            $count = mysqli_num_rows($res);

            if($count>0)
            {
                //Categories Available
                while($row=mysqli_fetch_assoc($res))
                {
                    //Get the Values like id, title, image_name
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
                    ?>
                    
                    <a href="category.php?subject_id=<?php echo $id; ?>">
                        
                        <div class="box-3 float-container">
                            <?php 
                                //Check whether Image is available or not
                                if($image_name=="")
                                {
                                    //Display MEssage
                                    echo "<div class='error'>Image not Available</div>";
                                }
                                else
                                {
                                    //Image Available
                                    ?>
                                    <img src="./images/category/<?php echo $image_name; ?>" alt="search-bg" class="img-responsive img-curve">
                                    <?php
                                }
                            ?>
                            

                            <h3 class="float-text text-white"><?php echo $title; ?></h3>
                        </div>
                    </a>

                    <?php
                }
            }
            else
            {
                //Categories not Available
                echo "<div class='error'>Category not Added.</div>";
            }
        ?>


        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->
</body>




<?php include('partials-front/footer.php') ?>