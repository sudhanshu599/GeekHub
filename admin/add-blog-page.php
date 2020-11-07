<?php require_once('../includes/config.php'); 

if(!$user->is_logged_in()){ header('Location: login.php'); }
?>

<?php include("head.php");  ?>
<!-- On page head area-->
<title>Add New Page | GeekHub</title>
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

<?php include("header.php"); 

   ?>

<div class="content">

    <h1 style="color:red">Add New Blog</h1>

    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        //collect form data
        extract($_POST);

        //very basic validations
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
            $error[] = 'Please enter the Page Keywords.';
        }

        if(!isset($error)){

          try {

    $page_slug = slug($page_title);

    //insert into database
   $stmt = $db->prepare('INSERT INTO pages (page_title,page_slug,page_desp,page_content,page_keywords) VALUES (:page_title, :page_slug, :page_desp, :page_content,:page_keywords)') ;
  



        $stmt->execute(array(
            ':page_title' => $page_title,
            ':page_slug' => $page_slug,
            ':page_desp' => $page_desp,
            ':page_content' => $page_content,
            ':page_keywords' => $page_keywords
        ));



    //redirect to index page
    header('Location: blog-pages.php?action=added');
    exit;

}catch(PDOException $e) {
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
    ?>
    <form action='' method='post'>
<br>
        <h3 style="color:blue">
            <label>Page Title</label><br>
            <input
                type='text'
                name='page_title'
                style="width:100%;height:40px"
                value='<?php if(isset($error)){ echo $_POST['page_title'];}?>'></h3>

        <h3 style="color:blue">
            <br>
            <label>Short Description(Meta Description)
            </label><br>
            <textarea name='page_desp' cols='120' rows='6'><?php if(isset($error)){ echo $_POST['page_desp'];}?></textarea>
        </h3>
<br>
        <h3 style="color:blue">
            <label>Long Description(Body Content)</label><br>
            <textarea
                name='page_content'
                id='textarea1'
                class='mceEditor'
                cols='120'
                rows='20'><?php if(isset($error)){ echo $_POST['page_content'];}?></textarea>
        </h3>
<br>
        <h3 style="color:blue">
            <label>Page Keywords (Seprated by comma without space)</label><br>
            <input
                type='text'
                name='page_keywords'
                value='<?php if(isset($error)){ echo $_POST['page_keywords'];}?>'
                style="width:100%;height:40px"></h3>
<br>
        <p><input type='submit' class="editbtn" name='submit' value='Submit'></p>

    </form>
</div>

<?php include("footer.php");  ?>