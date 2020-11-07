<?php 
//connection File 
require_once('includes/config.php'); ?>
<?php 
//include head file for language preference 
include("head.php");  ?>
<title>GeekHub Blog</title>
<style>
#keyword{
  border: #717476 1px solid; border-radius: 7px; padding: 30px;background:url("assets/images/search.png") no-repeat center right 7px;
}
.search_box{ 

text-align:right;margin:10px 0px;
}
.pagination_btn{
  margin-right:9px;
  padding:8px 20px;
  color:#FEFEFE;
   border: #009900 1px solid; 
   background:#66ad44;
    border-radius:4px;
    cursor:pointer;

}
.pagination_btn:hover{background:#010000;}
.pagination_btn.current{background:#FC0527;}
</style>

<?php include("header.php");  ?>

<div class="container">
    <div class="content">

    <?php   
        define("PER_PAGE_LIMIT",2); //Set blog posts limit
    $searching = '';
    if(!empty($_POST['search']['keyword'])) {
        $searching = $_POST['search']['keyword'];
    }
    /* PHP Blog Search*/
    $search_query = 'SELECT * FROM  blog WHERE blog_title LIKE :keyword OR blog_desp LIKE :keyword OR blog_tags LIKE :keyword OR blog_content LIKE :keyword ORDER BY blog_id DESC ';
  
    /* PHP Blog Pagination*/
    $per_page_item = '';
    $page = 1;
    $start=0;
    if(!empty($_POST["page"])) {
        $page = $_POST["page"];
        $start=($page-1) * PER_PAGE_LIMIT;
    }
    $limit=" limit " . $start . "," . PER_PAGE_LIMIT;
    $pagination_stmt = $db->prepare($search_query);
    $pagination_stmt->bindValue(':keyword', '%' . $searching . '%', PDO::PARAM_STR);
    $pagination_stmt->execute();

    $row_count = $pagination_stmt->rowCount();
    if(!empty($row_count)){
        $per_page_item .= '<div style="text-align:center;margin:0px 0px;">';
        $page_count=ceil($row_count/PER_PAGE_LIMIT);
        if($page_count>1) {
            for($i=1;$i<=$page_count;$i++){
                if($i==$page){
                    $per_page_item .= '<input type="submit" name="page" value="' . $i . '" class="pagination_btn current">';
                } else {
                    $per_page_item .= '<input type="submit" name="page" value="' . $i . '" class="pagination_btn">';
                }
            }
        }
        $per_page_item .= "</div>";
    }
    $query = $search_query.$limit;
    $pdo_stmt = $db->prepare($query);
    $pdo_stmt->bindValue(':keyword', '%' . $searching . '%', PDO::PARAM_STR);
    $pdo_stmt->execute();
    $result = $pdo_stmt->fetchAll();
?>
        <form action="" method="post">
            <div class="search_box"><input
                type="text"
                name="search[keyword]"
                value="<?php echo $searching; ?>"
                id="keyword"
                maxlength="30"></div>

            <?php
    if(!empty($result)) { 
        foreach($result as $row) {
    ?>

            <?php
                    //Blog Title 
                         echo '<h1><a href="'.$row['blog_slug'].'">'.$row['blog_title'].'</a></h1>';
                             echo '<hr>';
              //Blog post Date and Time 
                       echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['blog_datetime'])).' in ';
$stmt2 = $db->prepare('SELECT cat_name, cat_slug   FROM category, cat_blog WHERE category.cat_id = cat_blog.cat_id AND cat_blog.blog_id = :blog_id');
$stmt2->execute(array(':blog_id' => $row['blog_id']));
$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$links = array();
foreach ($catRow as $cat){
     $links[] = "<a href='category/".$cat['cat_slug']."'>".$cat['cat_name']."</a>";
}
echo implode(", ", $links);
echo '</p>';
echo '<p>Tagged as: ';
    $links = array();
    $parts = explode(',', $row['blog_tags']);
    foreach ($parts as $tags)
    {
        $links[] = "<a href='tag/".$tags."'>".$tags."</a>";
    }
    echo implode(", ", $links);
echo '</p>';
  echo '<hr>';
 echo '<p>'.$row['blog_desp'].'</p>'; 
  echo '<p><button class="readbtn"><a href="'.$row['blog_slug'].'">Read More</a></button></p>';                     
?>

        <?php
        }
    }
    else{ 
    echo "No results found for ". $searching;
    } 
    ?>
            <table>
                <tbody>
                    <tr></tr>
                </tbody>
            </table>
            <?php echo $per_page_item; ?>
        </form>
    </div>
    <?php //Sidebar Content  
include("sidebar.php");  ?>
</div>
<?php //footer content 
include("footer.php");  ?>