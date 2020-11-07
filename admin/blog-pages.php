<?php
//include connection file 
require_once('../includes/config.php');

//check login or not 
if(!$user->is_logged_in()){ header('Location: login.php'); }


if(isset($_GET['delpost'])){ 

    $stmt = $db->prepare('DELETE FROM pages WHERE page_id = :page_id') ;
    $stmt->execute(array(':page_id' => $_GET['delpost']));

    header('Location: blog-pages.php?action=deleted');
    exit;
} 


?>

<?php include("head.php");  ?>

<title>Admin Page
</title>
<script language="JavaScript" type="text/javascript">
    function delpost(id, title) {
        if (confirm("Are you sure you want to delete '" + title + "'")) {
            window.location.href = 'blog-pages.php?delpost=' + page_id;
        }
    }
</script>
<?php include("header.php");  ?>

<div class="content">
    <?php 
    //show message from add / edit page
    if(isset($_GET['action'])){ 
        echo '<h3>Post '.$_GET['action'].'.</h3>'; 
    } 
    ?>
<br>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
    
        <tr>
            <th>Blog Title</th>

            <th>Update</th>
            <th>Delete</th>
        </tr>
</thead>
        <?php
        try {

            $stmt = $db->query('SELECT page_id,page_title,page_desp,page_content,page_keywords FROM pages ORDER BY page_id DESC');
            while($row = $stmt->fetch()){
                
                echo '<tr>';
                echo '<td>'.$row['page_title'].'</td>';
                
                ?>

        <td>
            <button class="editbtn">
                <a href="edit-blog-page.php?page_id=<?php echo $row['page_id'];?>">Edit</a>
            </button>
        </td>
        <td>
            <button class="delbtn">
                <a
                    href="javascript:delpost('<?php echo $row['page_id'];?>','<?php echo $row['page_title'];?>')">Delete</a>
            </button>
        </td>

        <?php 
                echo '</tr>';

            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    ?>
    </table>

    <p>
        <button class="editbtn">
            <a href='add-blog-page.php'>Add New Page</a>
        </button>
    </p>
</div>
<?php include("sidebar.php");  ?>
<?php include("footer.php");  ?>