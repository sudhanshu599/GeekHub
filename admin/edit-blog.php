<?php require_once('../includes/config.php'); 

    if(!$user->is_logged_in()){ 
        header('Location: login.php'); 
    }
?>

<?php include("head.php"); ?>

    <title>Update Blog</title>
    <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
    <script>
        tinymce.init({
        mode : "specific_textareas",
        editor_selector : "mceEditor",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        });
    </script>

<?php include("header.php");  ?>

<div class="content">
 
<h2 style="color:red">Edit Post</h2>

<?php   
    if(isset($_POST['submit'])){

        //collect form data
        extract($_POST);

        if($blog_id ==''){
            $error[] = 'This post is missing a valid id!.';
        }

        if($blog_title ==''){
            $error[] = 'Please enter the title.';
        }

        if($blog_desp ==''){
            $error[] = 'Please enter the description.';
        }

        if($blog_content ==''){
            $error[] = 'Please enter the content.';
        }
        

        if(!isset($error)){
            try {
                $stmt = $db->prepare('UPDATE blog SET blog_title = :blog_title,blog_slug = :blog_slug,  blog_desp = :blog_desp, blog_content = :blog_content,blog_tags = :blog_tags WHERE blog_id = :blog_id') ;
                $stmt->execute(array(
                    ':blog_title' => $blog_title,
                    ':blog_slug' => $blog_slug,
                    ':blog_desp' => $blog_desp,
                    ':blog_content' => $blog_content,
                    ':blog_tags' => $blog_tags,
                    ':blog_id' => $blog_id,
                
                ));
                $stmt = $db->prepare('DELETE FROM cat_blog WHERE blog_id = :blog_id');
                $stmt->execute(array(':blog_id' => $blog_id));
                
                if(is_array($cat_id)){
                    foreach($_POST['cat_id'] as $cat_id){
                        $stmt = $db->prepare('INSERT INTO cat_blog (blog_id,cat_id)VALUES(:blog_id,:cat_id)');
                        $stmt->execute(array(
                            ':blog_id' => $blog_id,
                            ':cat_id' => $cat_id
                        ));
                    }
                }
                //redirect to index page
                header('Location: index.php?action=updated');
                exit;

            } 

            catch(PDOException $e) {
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
        $stmt = $db->prepare('SELECT blog_id,blog_title,blog_slug, blog_desp, blog_content , blog_tags FROM blog WHERE blog_id = :blog_id') ;
        $stmt->execute(array(':blog_id' => $_GET['id']));
        $row = $stmt->fetch(); 

    } 
    catch(PDOException $e) {
        echo $e->getMessage();
    }

?>
<br>
    <form action='' method='post'>
        <input type='hidden' name='blog_id' value="<?php echo $row['blog_id'];?>">
        <h3 style="color:blue"><label>Blog Title</label><br>

        <input type='text' name='blog_title' style="width:100%;height:40px" value="<?php echo $row['blog_title'];?>"></h3>
<br>
        <h3 style="color:blue"><label>Article Slug(Manual Customize)</label><br>
        <input type='text' name='blog_slug' style="width:100%;height:40px" value='<?php echo $row['blog_slug'];?>'></h3>
<br>
       <h3 style="color:blue"><label>Short Description(Meta Description) </label><br>
        <textarea name='blog_desp' cols='120' rows='6'><?php echo $row['blog_desp'];?></textarea></h3>
<br>
       <h3 style="color:blue"><label>Long Description(Body Content)</label><br>
        <textarea name='blog_content' id='textarea1' class='mceEditor' cols='120' rows='20'><?php echo $row['blog_content'];?></textarea></h3>
<br>
        <h3 style="color:blue"><label>Blog Tags (Seprated by comma without space)</label><br>
        <input type='text' name='blog_tags' style="width:100%;height:40px;"value='<?php echo $row['blog_tags'];?>'>
        <br></h3>
<br>
        <fieldset>
            <h3 style="color:blue"><legend>Categories</legend></h3>
<h4>
            <?php
            $checked = null;
            $stmt2 = $db->query('SELECT cat_id, cat_name FROM category ORDER BY cat_name');
            while($row2 = $stmt2->fetch()){

                $stmt3 = $db->prepare('SELECT cat_id FROM cat_blog WHERE cat_id = :cat_id AND blog_id = :blog_id') ;
                $stmt3->execute(array(
                    ':cat_id' => $row2['cat_id'], 
                    ':blog_id' => $row['blog_id']
                ));
                $row3 = $stmt3->fetch(); 
                
                if(isset($row3['cat_id']) == isset($row2['cat_id'])){
                    $checked = 'checked=checked';
                } else {
                    $checked = null;
                }

                echo "<input type='checkbox' name='cat_id[]' value='".$row2['cat_id']."' $checked> ".$row2['cat_name']."<br />";
            }

            ?>
            </h4>
            <br>
        </fieldset>
        <button name='submit' class="subbtn"> Update</button>

    </form>

</div>
  

<?php include("footer.php");  ?>