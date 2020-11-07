<?php require_once('../includes/config.php'); 
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<?php include("head.php");  ?>
<title>Update Page | GeekHub</title>
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script>
    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "mceEditor",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter align" +
                "right alignjustify | bullist numlist outdent indent | link image"
    });
</script>
<?php include("header.php");  ?>

<div class="content">

    <h2 style="color:red">Edit Post</h2>

    <?php

   
    if(isset($_POST['submit'])){

        //collect form data
        extract($_POST);

        //very basic validation
        if($page_id ==''){
            $error[] = 'Invalid ID .';
        }

        if($page_title ==''){
            $error[] = 'Please enter the Page title.';
        }

        if($page_desp ==''){
            $error[] = 'Please enter the Page description.';
        }

        if($page_content ==''){
            $error[] = 'Please enter the content.';
        }
          
        if($page_keywords ==''){
            $error[] = 'Please enter the Article Keywords.';
        }



        if(!isset($error)){
try {

   
 $page_slug = slug($page_title);
    //insert into database
    $stmt = $db->prepare('UPDATE pages SET page_title = :page_title, page_slug = :page_slug, page_desp = :page_desp, page_content = :page_content, page_keywords = :page_keywords WHERE page_id = :page_id') ;
$stmt->execute(array(
    ':page_title' => $page_title,
    ':page_slug' => $page_slug,
    ':page_desp' => $page_desp,
    ':page_content' => $page_content,
    ':page_id' => $page_id,
    ':page_keywords' => $page_keywords
));

    //redirect to index page
    header('Location: blog-pages.php?action=updated');
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

           $stmt = $db->prepare('SELECT page_id, page_slug,page_title, page_desp, page_content, page_keywords FROM pages WHERE page_id = :page_id') ;
            $stmt->execute(array(':page_id' => $_GET['page_id']));
            $row = $stmt->fetch(); 

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

    ?>

    <form action='' method='post'>
        <input type='hidden' name='page_id' value='<?php echo $row['page_id'];?>'>
<br>
        <h3 style="color:blue">
            <label>Page Title</label><br>
            <input
                type='text'
                name='page_title'
                style="width:100%;height:40px"
                value='<?php echo $row['page_title'];?>'></h3>
<br>
        <h3 style="color:blue">
            <label>Short Description(Meta Description)
            </label><br>
            <textarea name='page_desp' cols='120' rows='6'><?php echo $row['page_desp'];?></textarea>
        </h3>
<br>
        <h3 style="color:blue">
            <label>Long Description(Body Content)</label><br>
            <textarea
                name='page_content'
                id='textarea1'
                class='mceEditor'
                cols='120'
                rows='20'><?php echo $row['page_content'];?></textarea>
        </h3>
<br>
        <h3 style="color:blue">
            <label>Page Keywords (Seprated by comma without space)</label><br>
        <input type='text' name='page_keywords' style="width:100%;height:40px;" value='<?php echo $row['page_keywords'];?>' <br></h3>
<br>
        <input type='submit' class="editbtn" name='submit' value='Update'>

    </form>

</div>


<?php include("footer.php");  ?>