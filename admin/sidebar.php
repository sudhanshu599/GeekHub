<div class="w3-sidebar w3-bar-block w3-card" style="width:20%;right:0;">
  <h2 class="w3-bar-item">Quick Shortcut</h2>
  <a href="index.php" class="w3-bar-item w3-button">View Articles</a>
  <a href="add-blog.php" class="w3-bar-item w3-button">Add New Blog Post</a>
  <a href="blog-categories.php" class="w3-bar-item w3-button">View Categories</a>
  <a href="add-blog-category.php" class="w3-bar-item w3-button">Add New Category</a>
  <a href="blog-users.php" class="w3-bar-item w3-button">View Users</a>
  <a href="add-blog-page.php" class="w3-bar-item w3-button">Add New Page</a>
  <a href="blog-pages.php" class="w3-bar-item w3-button">View Pages</a>
  <a href="add-blog-user.php" class="w3-bar-item w3-button">Add New Users</a>
  <a target="_blank" href="../" class="w3-bar-item w3-button">Visit Blog</a>

    <?php 
  $sql = $db->query('select count(*) from blog')->fetchColumn(); 
echo'<h2 class="w3-bar-item">Total Posted '.'<font color="red">'.$sql.'</font>'.'</h2>' ;
?>

</div>