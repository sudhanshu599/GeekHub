<?php require('includes/config.php'); ?>

<?php include("head.php");  ?>

    <title><?php echo htmlspecialchars($_GET['id']);?> | GeekHub</title>

<?php include("header.php");  ?>
<div class="content">


    <p>Blog in tag:- <?php echo htmlspecialchars($_GET['id']);?></p>
        <hr>
       
        <?php   
            try {

                $stmt = $db->prepare('SELECT blog_id, blog_title, blog_slug, blog_desp, blog_datetime, blog_tags FROM blog WHERE blog_tags like :blog_tags ORDER BY blog_id DESC');
                $stmt->execute(array(':blog_tags' => '%'.$_GET['id'].'%'));
                while($row = $stmt->fetch()){
                    
                    echo '<div>';
                        echo '<h1><a href="../'.$row['blog_slug'].'">'.$row['blog_title'].'</a></h1>';
                        echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['blog_datetime'])).' in ';

                            $stmt2 = $db->prepare('SELECT cat_name, cat_slug FROM category, cat_blog WHERE category.cat_id = cat_blog.cat_id AND cat_blog.blog_id = :blog_id');
                            $stmt2->execute(array(':blog_id' => $row['blog_id']));

                            $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                            $links = array();
                            foreach ($catRow as $cat)
                            {
                                $links[] = "<a href='../category/".$cat['cat_slug']."'>".$cat['cat_name']."</a>";
                            }
                            echo implode(", ", $links);

                        echo '</p>';
                    echo '<p>Tagged as: ';
                    $links = array();
                    $parts = explode(',', $row['blog_tags']);
                    foreach ($parts as $tags)
                    {
                      $links[] = "<a href='".$tags."'>".$tags."</a>";
                    }
                    echo implode(", ", $links);
                    echo '</p>';
                        echo '<p>'.$row['blog_desp'].'</p>';               
                        echo '<p><button class="readbtn"><a href="../'.$row['blog_slug'].'">Read More</a></button></p>'; 


                    echo '</div>';

                }

            } catch(PDOException $e) {
                echo $e->getMessage();
            }

            ?>
</div>
<?php include("sidebar.php");  ?>

<?php include("footer.php");  ?>