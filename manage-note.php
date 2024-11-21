<?php 

include('partials-front/navbar.php'); 

?>

<?php


// Display status messages
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}

?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Notes</h1>

        <br><br>

                
                

                <br><br><br>

                <?php 
                        

                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add'];
                        unset($_SESSION['add']);
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];
                        unset($_SESSION['delete']);
                    }

                    if(isset($_SESSION['upload']))
                    {
                        echo $_SESSION['upload'];
                        unset($_SESSION['upload']);
                    }

                    if(isset($_SESSION['unauthorize']))
                    {
                        echo $_SESSION['unauthorize'];
                        unset($_SESSION['unauthorize']);
                    }

                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                
                ?>

                <table class="tbl-full">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        
                        <th>Image</th>
                        
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                        
                        $sql = "SELECT * FROM tbl_notes";
                        $res = mysqli_query($conn, $sql);

                        if ($res && mysqli_num_rows($res) > 0) {
                            $sn = 1; // Serial Number
                            while ($row = mysqli_fetch_assoc($res)) {
                                $note_id = $row['note_id'];
                                $title = $row['title'];
                                $image_name = $row['image_name'];
                                $doc_name = $row['doc_name'];
                                $active = $row['active'];
                     ?>

                                <tr>
                                    <td><?php echo $sn++; ?>. </td>
                                    <td><?php echo $title; ?></td>
                                    
                                    <td>
                                        <?php  
                                            //CHeck whether we have image or not
                                            if($image_name=="")
                                            {
                                                //WE do not have image, DIslpay Error Message
                                                echo "<div class='error'>Image not Added.</div>";
                                            }
                                            else
                                            {
                                                //WE Have Image, Display Image
                                                ?>
                                                <img src="../images/food/<?php echo $image_name; ?>" width="100px">
                                                <?php
                                            }
                                        ?>
                                    </td>
                                    
                                    <td><?php echo $active; ?></td>
                                    <td>
                                        <a href="update-food.php?id=<?php echo $id; ?>" class="btn-secondary">Update</a>
                                        <a href="delete-food.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>&doc_name=<?php echo $doc_name; ?>" class="btn-danger">Delete</a>
                                    </td>
                                </tr>

                                <?php
                            }
                        }
                        else
                        {
                            //Added in Database
                            echo "<tr> <td colspan='7' class='error'> Notes not Added Yet. </td> </tr>";
                        }

                    ?>

                    
                </table>
    </div>
    
</div>