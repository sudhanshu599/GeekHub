<div class="sidebar">
    <h2>Recent Articles</h2>
    <?php
        $sidebar = $db->query('SELECT blog_title, blog_slug FROM blog ORDER BY blog_id DESC LIMIT 6');
        while($row = $sidebar->fetch()){
            echo ' <a href="http://localhost/GeekHub/'.$row['blog_slug'].'" >'.$row['blog_title'].' </a >';
        }
    ?>
    <h2>Categories</h2>

    <?php
        $stmt = $db->query('SELECT cat_name, cat_slug FROM category ORDER BY cat_id DESC');
        while($row = $stmt->fetch()){
            echo '<a href="http://localhost/GeekHub/category/'.$row['cat_slug'].'">'.$row['cat_name'].'</a>';
        }
    ?>

    <h2>Tags</h2>
    <?php
        $tagsArray = [];
        $stmt = $db->query('select distinct LOWER(blog_tags) as blog_tags from blog where blog_tags != "" group by blog_tags');
        while($row = $stmt->fetch()){
            $parts = explode(',', $row['blog_tags']);
            foreach ($parts as $tag) {
                $tagsArray[] = $tag;
            }
        }

        $finalTags = array_unique($tagsArray);
        foreach ($finalTags as $tag) {
            echo "<a href='http://localhost/GeekHub/tag/".$tag."'>".ucwords($tag)."</a>";
        }
    ?>

    

</div>