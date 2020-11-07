<?php
    require_once('../includes/config.php');
    if($user->is_logged_in()){
        header('location:index.php');
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
body {
  background-image: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('background.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed; 
  background-size: 100% 100%;
}
</style>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
    <link rel="stylesheet" href="style1.css">
    
    
</head>
<body>
    <?php

    if(isset($_POST['submit'])){

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if($user->login($username,$password)){
            header('location: index.php');
            exit;
        }
        else{
            $message = '<p class="invalid">Invalid Username or Password</p>';
        }

    }
    if(isset($message)){
        echo $message;
    }


    ?>
<div class="container">
<div class="col-lg-6 m-auto d-block">
<div class="c1">
            <div class="text-success" id="temp"></div>
            <div class="text-danger" id="temp1"></div>
            <div class="form-group">
    <form action="" method="POST" class="form">
    <div class="form-group">
    <label class="font-weight-bold text-white">Username</label>
    <input type="text" class="form-control" name="username" placeholder="Enter Username" value="" required/>
    </div>
    <div class="form-group">
    <label class="font-weight-bold text-white">Password</label>
    <input type="password" class="form-control" name="password" placeholder="Enter Password" value="" required/>
    </div>
    <label></label>
    <input class="btn btn-primary"type="submit" name="submit" value="SignIn" />
</form>
</div>
</body>
</html>

