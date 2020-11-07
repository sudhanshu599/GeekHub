<?php //include connection file 
    require_once('../includes/config.php');

    if(!$user->is_logged_in()){ header('Location: login.php'); }
?>

<?php include("head.php");  ?>
<title>EDIT Category | GeekHub</title>
<?php include("header.php");  ?>

<div class="content">
    <h2>Edit Category | GeekHub</h2>

    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        $_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($cat_id ==''){
            $error[] = 'Invalid id.';
        }

        if($cat_name ==''){
            $error[] = 'Please enter the Category Title .';
        }

        if(!isset($error)){

            try {

                $cat_slug = slug($cat_name);

                //insert into database
                $stmt = $db->prepare('UPDATE category SET cat_name = :cat_name, cat_slug = :cat_slug WHERE cat_id = :cat_id') ;
                $stmt->execute(array(
                    ':cat_name' => $cat_name,
                    ':cat_slug' => $cat_slug,
                    ':cat_id' => $cat_id
                ));

                //redirect to categories  page
                header('Location: blog-categories.php?action=updated');
                exit;

            } catch(PDOException $e) {
                echo $e->getMessage();
            }

        }

    }

    ?>

    <?php
    //check for any errors
    if(isset($error)){
        foreach($error as $error){
            echo $error.'<br>';
        }
    }

        try {

            $stmt = $db->prepare('SELECT cat_id, cat_name FROM category WHERE cat_id = :cat_id') ;
            $stmt->execute(array(':cat_id' => $_GET['id']));
            $row = $stmt->fetch(); 

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

    ?>

    <form action="" method="post">
        <input type='hidden' name='cat_id' value='<?php echo $row['cat_id'];?>'>

        <p>
            <label>Category Title</label><br>
            <input
                type='text'
                name='cat_name'
                value='<?php echo $row['cat_name'];?>'>

        </p>
        <p><input type="submit" name="submit" value="Update"></p>

    </form>

</div>
<?php include("sidebar.php");  ?>

<?php include("footer.php");  ?>