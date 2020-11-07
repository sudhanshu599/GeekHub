<?php 
//sitemap.php to sitemap.xml using .htaccess file 
require_once('../includes/config.php');

$pages = $db->query('SELECT page_slug FROM pages ORDER BY page_id ASC');
$article = $db->query('SELECT blog_slug FROM blog ORDER BY blog_id ASC');
$category = $db->query('SELECT cat_slug FROM category ORDER BY cat_id ASC');
$tag= $db->query('SELECT blog_tags FROM blog ORDER BY blog_id ASC');
               

//define your base URLs 
//Main URL 
$base_url = "http://localhost/GeekHub/";
//Page base URL 
$page_base_url = "http://localhost/GeekHub/page/";
//Category base URL
$category_base_url = "http://localhost/GeekHub/category/";
//tag base URL
$tag_base_url = "http://localhost/GeekHub/tag/";



header("Content-Type: application/xml; charset=utf-8");

echo '<!--?xml version="1.0" encoding="UTF-8"?-->'.PHP_EOL; 

echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemalocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
echo '<url>' . PHP_EOL;
 echo '<loc>'.$base_url.'</loc>' . PHP_EOL;
 echo '<changefreq>daily</changefreq>' . PHP_EOL;
 echo '</url>' . PHP_EOL;


while($row = $pages->fetch()){

 echo '<url>' . PHP_EOL;
 echo '<loc>'.$page_base_url. $row["page_slug"] .'</loc>' . PHP_EOL;
 echo '<changefreq>daily</changefreq>' . PHP_EOL;
 echo '</url>' . PHP_EOL;
}


 while($row = $article->fetch()){

 echo '<url>' . PHP_EOL;
 echo '<loc>'.$base_url. $row["blog_slug"] .'</loc>' . PHP_EOL;
 echo '<changefreq>daily</changefreq>' . PHP_EOL;
 echo '</url>' . PHP_EOL;
}
while($row = $category->fetch()){

 echo '<url>' . PHP_EOL;
 echo '<loc>'.$category_base_url. $row["cat_slug"] .'</loc>' . PHP_EOL;
 echo '<changefreq>daily</changefreq>' . PHP_EOL;
 echo '</url>' . PHP_EOL;
}
while($row = $tag->fetch()){

 echo '<url>' . PHP_EOL;
 echo '<loc>'.$tag_base_url. $row["blog_tags"] .'</loc>' . PHP_EOL;
 echo '<changefreq>daily</changefreq>' . PHP_EOL;
 echo '</url>' . PHP_EOL;
}



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
        
        echo '<url>' . PHP_EOL;
 echo '<loc>'.$tag_base_url.$tag.'</loc>' . PHP_EOL;
 echo '<changefreq>daily</changefreq>' . PHP_EOL;
 echo '</url>' . PHP_EOL;
    }

echo '</urlset>' . PHP_EOL;

?>