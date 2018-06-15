<?php
require_once ('core/init.php');

//check email and token from click link
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['email_token']) && !empty($_GET['email_token'])){
 // if required data is present 
  $email = mysqli_escape_string($connection,$_GET['email']);
  $email_token = mysqli_escape_string($connection,$_GET['email_token']);
  
  $searchQuery = "select * from users where email='$email' AND email_token='$email_token' AND verified ='0'";
  
  //check data from url against data in the database
  $search = mysqli_query($connection, $searchQuery) or die(mysqli_error($connection));
  $match = mysqli_num_rows($search); //store results of query in match variable 
  
  //check if we have a match
  if($match>0){
      //we have a match
      
      //update verification status 
      $updateVerifiedStatusQuery = "update users set verified = '1' where email='$email' AND email_token = '$email_token' AND verified = '0'";
      $updateVerifiedStatus = mysqli_query($connection, $updateVerifiedStatusQuery) or die(mysqli_error($connection));
      $show = '<h3 style="font-weight: bolder; text-align: center">Email Confirmation</h3></br>
               <div class="statusmsg" style="font-weight: bolder; text-align:center">
               <p style="font-weight: bolder; text-align:center">Your Account has been activated!</br><a href="index.php">Login</a></p></div>';
      
      //delete email token
      $deleteTokenQuery = "update users set email_token = null where email = '$email' and email_token='$email_token'";
      $deleteToken = mysqli_query($connection, $deleteTokenQuery) or die (mysqli_error($connection));
  }
  else{
      //we dont have a match
   $show = '<h3 style="font-weight: bolder; text-align: center">Email Confirmation</h3></br>
      <h5 style="text-align:center">You clicked an invalid link or your account has already been activated</h5></br>
      <h5 style="text-align:center"><a href="index.php">Login</a></h5>';
  }
  
} else {
  //data not present 
    
     $show = '<h2 style="font-weight: bolder; text-align: center">Email Confirmation</h2></br>
             <h5 style="text-align:center">Invalid Verification Method</h5>';
     
 
}
?>


<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyNc | Email Confirmation</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/register_styles.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
     <link href="https://fonts.googleapis.com/css?family=Arvo|Gugi|Patua+One|Pontano+Sans|Titan+One|Yanone+Kaffeesatz" rel="stylesheet">
</head>

<body>
    
     <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean-button" style="color:rgb(0,0,0);background-color:rgb(0,0,0); margin-bottom: 30px">
            <div class="container-fluid"><a class="navbar-brand" href="index.php" style="font-size:30px;color:rgb(255,255,255);">SyNc</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div
                    class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav mr-auto"></ul><span class="navbar-text actions"> 
                        <a class="btn btn-light action-button" role="button" data-toggle="modal" data-target="#about_us_modal" style="margin-right: 10px">About Us</a>
                        <a class="btn btn-light action-button" role="button" href="index.php" style="background-color:#56c6c6;">Login</a></span></div>
    </div>
    </nav>
    </div> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>

