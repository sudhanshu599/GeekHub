<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT page_id,page_title,page_slug,page_content,page_desp,page_keywords FROM pages WHERE page_slug = :page_slug');
$stmt->execute(array(':page_slug' => $_GET['page_id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['page_id'] == ''){
    header('Location: ./');
    exit;
}


?>

<?php include("head.php");  ?>

  <title><?php echo $row['page_title'];?></title>
  <meta name="description" content="<?php echo $row['page_desp'];?>">    
<meta name="keywords" content="<?php echo $row['page_keywords'];?>">

  <?php include("header.php");  ?>
<div class="content">
 
<?php
echo '<h1>'.$row['page_title'].'</h1>';
?>
<hr> 
<?php 
echo '<p>'.$row['page_content'].'</p>';
       
?>
</div>
<?php include("sidebar.php");  ?>

<?php include("footer.php");  ?>
 