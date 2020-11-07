<?php
error_reporting(0);
ini_set('display_errors',0);    
?>

<?php require_once('../includes/config.php'); 

    if(!$user->is_logged_in()){ 
        header('Location: login.php'); 
    }
?>

<?php include("head.php");  ?>

<!-- On page head area--> 
  <title>Add New Blog</title>
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

  <?php include("header.php"); ?>

<div class="content">
 
    <h1 style="color:red">Add New Blog</h1>

<?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        //collect form data
        extract($_POST);

        //very basic validations
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
                //insert into database
                $blog_slug = slug($blog_title);
                $stmt = $db->prepare('INSERT INTO blog (blog_title,blog_slug,blog_desp,blog_content,blog_datetime,blog_tags) 
                                        VALUES (:blog_title,:blog_slug, :blog_desp, :blog_content, :blog_datetime, :blog_tags)');
    

                $stmt->execute(array(
                    ':blog_title' => $blog_title,
                    ':blog_slug' => $blog_slug,
                    ':blog_desp' => $blog_desp,
                    ':blog_content' => $blog_content,
                    ':blog_datetime' => date('Y-m-d H:i:s'),
                    ':blog_tags' => $blog_tags,
                ));
                //add categories
                $blog_id = $db->lastInsertId();
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
                header('Location: index.php?action=added');
                exit;

            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    //check for any errors
    if(isset($error)){
        foreach($error as $error){
            echo '<p class="message">'.$error.'</p>';
        }
    }
    try {
        $stmt = $db->prepare('SELECT blog_id,blog_title,blog_slug, blog_desp, blog_content FROM blog WHERE blog_id = :blog_id') ;
        $stmt->execute(array(':blog_id' => $_GET['id']));
        $row = $stmt->fetch(); 

    } 
    catch(PDOException $e) {
        echo $e->getMessage();
    }

?>
<br>
    <form action="" method="post">

        <h3 style="color:blue"><label>Article Title</label><br>
        <input type="text" name="blog_title" style="width:100%;height:40px" value="<?php if(isset($error)){ echo $_POST['blog_title'];}?>"></h3>
<br>
        <h3 style="color:blue"><label>Short Description(Meta Description) </label><br>
        <textarea name="blog_desp" cols="120" rows="6"><?php if(isset($error)){ echo $_POST['blog_desp'];}?></textarea></h3>
<br>
        <h3 style="color:blue"><label>Long Description(Body Content)</label><br>
        <textarea name="blog_content" id="textarea1" class="mceEditor" cols="120" rows='20'><?php if(isset($error)){ echo $_POST['blog_content'];}?></textarea></h3>
<br>
        <h3 style="color:blue"><label>Blog Tags (Separated by comma without space)</label><br>
        <input type='text' name='blog_tags' value='<?php if(isset($error)){ echo $_POST['blog_tags'];}?>' style="width:100%;height:40px"></h3>
<br>
        <fieldset>
            <h3 style="color:blue"><legend>Categories</legend> </h3>
<h4>
                <?php
                $checked = null;
                $stmt2 = $db->query('SELECT cat_id, cat_name FROM category ORDER BY cat_name');
                while($row2 = $stmt2->fetch()){

                    $stmt3 = $db->prepare('SELECT cat_id FROM cat_blog WHERE cat_id = :cat_id AND blog_id = :blog_id') ;
                    $stmt3->execute(array(':cat_id' => $row2['cat_id'], ':blog_id' => $row['blog_id']));
                    $row3 = $stmt3->fetch(); 

                    if(isset($row3['cat_id']) == isset($row2['cat_id'])){
                        $checked = 'checked=checked';
                    } 
                    else {
                        $checked = null;
                    }

                    echo "<input type='checkbox' name='cat_id[]' value='".$row2['cat_id']."' $checked> ".$row2['cat_name']."<br />";
                }

                ?>
            </h4>
        </fieldset>
        <button name="submit" class="subbtn">Submit</button>

    </form>

</div>

<?php include("footer.php");  ?>