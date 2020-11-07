<?php
    //include connection file 
    require_once('../includes/config.php');

    //check logged in or not 
    if(!$user->is_logged_in()){ 
        header('Location: login.php'); 
    }

    // add / edit page
    if(isset($_GET['deluser'])){ 

    
    if($_GET['deluser'] !='1'){

        $stmt = $db->prepare('DELETE FROM users WHERE user_id = :user_id') ;
        $stmt->execute(array(':user_id' => $_GET['deluser']));

        header('Location: blog-users.php?action=deleted');
        exit;

    }
    } 

?>

<?php include("head.php");  ?>
  <title>Users - GeekHub Blog</title>
  <script language="JavaScript" type="text/javascript">
  function deluser(id, title)
  {
    if (confirm("Are you sure you want to delete '" + title + "'"))
    {
      window.location.href = 'blog-users.php?deluser=' + id;
    }
  }
  </script>
  <?php include("header.php");  ?>

<div class="content">
 <?php 
  //show message from add / edit page
  if(isset($_GET['action'])){ 
    echo '<h3>User '.$_GET['action'].'.</h3>'; 
  } 
  ?>
  <br>

  <table class="table table-bordered">
    <thead class="thead-dark">
  <tr>
    <th>Username </th>
    <th>Email </th>
    <th>Edit </th>
    <th>Delete </th>

  </tr>
</thead>

   <?php
    try {

      $stmt = $db->query('SELECT user_id, username, email FROM users ORDER BY user_id');
      while($row = $stmt->fetch()){
        
        echo ' <tr>';
        echo ' <td>'.$row['username'].' </td>';
        echo ' <td>'.$row['email'].' </td>';
        ?>

        <td>
          <button class="editbtn"><a href="edit-blog-user.php?id=<?php echo $row['user_id'];?>">Edit</a> </button>
          <?php if($row['user_id'] != 1){?>
          </td>
            <td><button class="delbtn"><a href="javascript:deluser('<?php echo $row['user_id'];?>','<?php echo $row['username'];?>')">Delete</a></button>

          <?php } 

          ?>
        </td>
        
        <?php 
        echo '</tr>';

      }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
  ?>
  </table>

  <p><button class="editbtn"><a href='add-blog-user.php'>Add User</a></button></p>


</div>
  


<?php include("sidebar.php");  ?>
<?php include("footer.php");  ?>



