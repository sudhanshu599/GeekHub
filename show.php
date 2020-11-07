<?php require('includes/config.php'); 

    $stmt = $db->prepare('SELECT blog_id, blog_desp, blog_title, blog_slug, blog_content, blog_datetime,blog_tags FROM blog WHERE blog_slug = :blog_slug');
    $stmt->execute(array(':blog_slug' => $_GET['id']));
    $row = $stmt->fetch();

    //if post does not exists redirect user.
    if($row['blog_id'] == ''){
        header('Location: ./');
        exit;
    }

$cid = $row['blog_id'];
?>

<?php include("head.php");  ?>

    <title><?php echo $row['blog_title'];?>-GeekHub</title>
    <meta name="description" content="<?php echo $row['blog_desp'];?>">    
    <meta name="keywords" content="<?php echo $row['blog_tags'];?>">

<?php include("header.php");  ?>
<div class="container">
    <div class="content">

        <?php
            echo '<div>';
                echo '<h1>'.$row['blog_title'].'</h1>';

                echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['blog_datetime'])).' in ';

                $stmt2 = $db->prepare('SELECT cat_name, cat_slug   FROM category, cat_blog WHERE category.cat_id = cat_blog.cat_id AND cat_blog.blog_id = :blog_id');
                $stmt2->execute(array(':blog_id' => $row['blog_id']));
                
                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $links = array();
                foreach ($catRow as $cat){
                     $links[] = "<a href='category/".$cat['cat_slug']."'>".$cat['cat_name']."</a>";
                }
                echo implode(", ", $links);
                
                
                echo '<p>Tagged as: ';
                    $links = array();
                    $parts = explode(',', $row['blog_tags']);
                    foreach ($parts as $tags)
                    {
                    $links[] = "<a href='".$tags."'>".$tags."</a>";
                    }
                    echo implode(", ", $links);
                echo '</p>';
 
                echo '</p>';
                  echo '<hr>';
                
                echo '<p>'.$row['blog_content'].'</p>';    

            echo '</div>';
        ?>
         <h2> Recomended Posts:</h2>
            <?php

            $recom= $db->query("SELECT * from blog where blog_id>$cid order by blog_id ASC limit 5");

            // look through query
                while($row1 = $recom->fetch()){
                    echo '<h2><a href="'.$row1['blog_slug'].'">'.$row1['blog_title'].'</a></h2>';
            }
            ?>

            <h2> Previous Posts:</h2>
            <?php

            // run query//select by current id and display the previous 5 posts

            $previous= $db->query("SELECT * from blog where blog_id<$cid order by blog_id DESC limit 5");

            // look through query
                while($row1 = $previous->fetch()){
                    echo '<h2><a href="'.$row1['blog_slug'].'">'.$row1['blog_title'].'</a></h2>';

                }
            ?>
            <div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://localhost-yervjmnvbu.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                            

    </div>
<?php include("sidebar.php");  ?>

</div>

<?php include("footer.php");  ?>