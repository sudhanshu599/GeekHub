<?php 
    //include connection file 
    require_once('../includes/config.php');

    //check login or not 
    if(!$user->is_logged_in()){ 
        header('Location: login.php'); 
    }


    if(isset($_GET['delpost'])){ 

        $stmt = $db->prepare('DELETE FROM blog WHERE blog_id = :blog_id') ;
        $stmt->execute(array(':blog_id' => $_GET['delpost']));

        header('Location: index.php?action=deleted');
        exit;
    } 

?>

<?php include("head.php");  ?>

  <title>Admin Page </title>
  <script language="JavaScript" type="text/javascript">
  function delpost(id, title){
      if (confirm("Are you sure you want to delete '" + title + "'")){
          window.location.href = 'index.php?delpost=' + id;
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
<table class="table table-bordered">
    <thead class="thead-dark">
    <tr>
        <th>Article Title</th>
        <th>Posted Date</th>
        <th>Update</th>
         <th>Delete</th>
    </tr>
    <thead>
    <?php
        try {

        $stmt = $db->query('SELECT blog_id, blog_title, blog_datetime FROM blog ORDER BY blog_id DESC');
        while($row = $stmt->fetch()){
            
            echo '<tr>';
            echo '<td>'.$row['blog_title'].'</td>';
            echo '<td>'.date('jS M Y', strtotime($row['blog_datetime'])).'</td>';
    ?>

            <td>
                <button class="editbtn" > 
                    <a href="edit-blog.php?id=
                        <?php echo $row['blog_id'];?>">Edit 
                    </a>  
                </button>
            </td> 

            <td>
                <button class="delbtn">   
                    <a href="javascript:delpost('
                        <?php echo $row['blog_id'];?>','
                        <?php echo $row['blog_title'];?>')" >Delete 
                    </a > 
                </button >
            </td>
                
    <?php 
                echo '</tr>';
            }
        } 
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    ?>
    </table>

    <p> <button class="editbtn"><a href='add-blog.php'>Add New Article</a></button></p>       
    </p>
    </div>
    <?php include("sidebar.php");  ?>
<?php include("footer.php");  ?> 