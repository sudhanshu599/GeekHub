<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT cat_id,cat_name FROM category WHERE cat_slug = :cat_slug');
$stmt->execute(array(':cat_slug' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['cat_id'] == ''){
    header('Location: ./');
    exit;
}


?>

<?php include("head.php");  ?>

	<title><?php echo $row['cat_name'];?> | GeekHub</title>
	<?php include("header.php");  ?>
<div class="content">
 

<p>Article Category:- <?php echo $row['cat_name'];?></p>
        <hr>
       

        <?php    
        try {

            $stmt = $db->prepare('
                SELECT 
                    blog.blog_id, blog.blog_title, blog.blog_slug, blog.blog_desp, blog.blog_datetime 
                FROM 
                    blog,
                    cat_blog
                WHERE
                     blog.blog_id =  cat_blog.blog_id
                     AND  cat_blog.cat_id = :cat_id
                ORDER BY 
                    blog_id DESC
                ');
            $stmt->execute(array(':cat_id' => $row['cat_id']));
            while($row = $stmt->fetch()){
                
                echo '<div>';
                    echo '<h1><a href="../'.$row['blog_slug'].'">'.$row['blog_title'].'</a></h1>';
                    echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['blog_datetime'])).' in ';

                        $stmt2 = $db->prepare('SELECT cat_name, cat_slug   FROM category, cat_blog WHERE category.cat_id = cat_blog.cat_id AND cat_blog.blog_id = :blog_id');
                        $stmt2->execute(array(':blog_id' => $row['blog_id']));

                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                        $links = array();
                        foreach ($catRow as $cat)
                        {
                            $links[] = "<a href='".$cat['cat_slug']."'>".$cat['cat_name']."</a>";
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