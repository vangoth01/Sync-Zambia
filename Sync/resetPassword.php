<?php
    require_once 'core/init.php';
    
    if(isset($_POST['sign_up'])){
       // if(isset($_GET['email']) && isset($_GET['email_token'])){
         $email = mysqli_escape_string($connection,$_GET['email']);
         $email_token = mysqli_escape_string($connection, $_GET['email_token']);
         
         $password = $_POST['password'];
         $passwordConfirm = $_POST['passwordConfirm'];
         
         $query = "select * from users where email='$email' and email_token = '$email_token'"
                 . " and email_token is not null and email_token_expire > now()";
         
         $sql = mysqli_query($connection, $query) or die ("I am select".mysqli_error($connection));
         $count = mysqli_num_rows($sql);
         
          if ($count > 0){
                 
                  //hash password
                  $hashed_password = password_hash($password,PASSWORD_DEFAULT);
    
                    //password confirmation
                    //if password matches
                    if(password_verify($passwordConfirm, $hashed_password)){
                    
                         
                        $sql_query = "update users set password = '$hashed_password' where email = '$email' and email_token='$email_token'";
                        $deleteTokenQuery = "update users set email_token = null, email_token_expire = null where email = '$email' and email_token='$email_token'";
      
                        //update password
                        $sql = mysqli_query($connection, $sql_query) or die ("I am Update Error: ".mysqli_error($connection)); 
                        //clear tokens and expiry time
                        
                        if($sql){ //if password updated
                            $deleteToken = mysqli_query($connection, $deleteTokenQuery) or die (mysqli_error($connection));
                            $errorMessage = "Password Has been reset, You will now be redirected";
                            header("Refresh: 4; url=index.php");
                            
                        }//end password update check
                        
                       }//end password match
                       else {
                          $errorMessage = "Passwords Did Not Match, Please Confirm Inputs"; 
                       }
                }//end select count
                else{
                    $errorMessage = "Token has Expired or Email Is Not Valid, Please Try Again";
                }
             /* }//end is set get
               else{
              $errorMessage = 'Invalid Password Recovery(GET), Please try again';
                }*/
          }//end is set post
        /*  else{
              $errorMessage = 'Invalid Password Recovery(POST), Please try again';
          }*/
    
?>
<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyNc | Reset Password</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/resetPass_styles.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
     <link href="https://fonts.googleapis.com/css?family=Arvo|Gugi|Patua+One|Pontano+Sans|Titan+One|Yanone+Kaffeesatz" rel="stylesheet">
</head>

<body style="background-image:url(&quot;assets/img/cover.jpg&quot;);">
 
   <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean-button" style="color:rgb(0,0,0);background-color:rgb(0,0,0);">
            <div class="container-fluid"><a class="navbar-brand" href="index.php" style="font-size:30px;color:rgb(255,255,255);">SyNc</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div
                    class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav mr-auto"></ul><span class="navbar-text actions"> 
                        <a class="btn btn-light action-button" role="button" data-toggle="modal" data-target="#about_us_modal" style="margin-right: 10px">About Us</a>
                        <a class="btn btn-light action-button" role="button" href="index.php" style="background-color:#56c6c6;">Login</a></span></div>
    </div>
    </nav>
    </div> 
    
     <div class="container" style="padding-top:50px;padding-bottom:40px;">
            <div class="row">
                <div class="col-md-12 align-self-center">
                    <div>
                        <i class="icon-lock" style="color:rgb(255,255,255);font-size:100px;margin-bottom:0px;padding-bottom:0px;"></i>
                        <form method="post">
                        <div>
                            <label style="margin-bottom:30px;margin-top:25px;">Reset Your Password</label>
                        </div>
                            <div>
                                <?php if(isset($errorMessage)){?> <p style="color: aqua;text-align: center; font-weight: bolder"> <?php
                                echo $errorMessage ;?> </p>
                                <?php } ?>
                            </div>
                            <div style="margin-top:20px">
                            <label>New Password</label></div>
                            <input type="password" name="password" required="" placeholder="Enter New Password">
                        <div>
                            <label>Confirm New Password</label></div>
                            <input type="password" name="passwordConfirm" required="" placeholder="Confirm New Password">
                        <div>
                            <button class="btn btn-info" name="sign_up" type="submit" style="width:150px;">Change Password</button>
                        </div>
                            <br> <br> 
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
   <div class="modal fade" id="about_us_modal" tabindex="-1" role="dialog" aria-labelledby="about_us_modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-dialog modal-lg">
                <div style="background: white; color: black" class="modal-header">
                    <h5 class="modal-title"> About Us</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: white; color: black">
                    <div class="container-fluid">
                        <div class="row">
                            <div >
                                <h3 style="text-align:center; font-family:'Titan One', cursive;">
                                    SyNc Zambia
                                </h3>
                                
                                <p style="text-align:center">
                                    jjkfksbcsbjkss sbkshbcsc shbchsbcsbchjs  schscbsjhvcjsc svcjhvsjchvcvs sjvcjhsvcjhs-sssshbcsh chbvsjvhcsvc sh vhjsvhjsv svjh shvjhsv svh svjhs
                                    scshvshbvhs svhsvcjhsvjcvs schvcsjhvcjhvs sch schvjscsvcs schcvjsvcjscjs csgjvcajgvjacv scjsvcjvsjcvs
                                    <br><br>
                                    svhbvhkbsv vshbvkhsbvhs vhbvkhsbvskv vkhsbvbshbvshbvs vsbvhjbsvvhsascsyuuyshcvjsc sv bjhvschjsvcsc csj cgshvchgsvchscc scgsvcghvsgsc scghsvcgsvgcs sgcvscvjS csghvc
                                    csc vsgjvcjgsc  hvjhsvbjhbsvhjs vh jhvjhvhjcvscsh chvjhxchjcvjhxc chjvcjhxvjhxv
                                    <br><br>
                                    hsbcjsbcjs shsbhvbsbvss svj sbvkhsbvs svbskvhsbvksbv shvbhsbvshbvsv hsv skbvshkbvksbv svhs bvhsbvksbv svhsbvhsbvs vhsvbshvbshvb
                                    vshbshkbvhsk svjsbvksvsjbksv svkbvksbvksjbvs shvbshkbvksbv vshkbvskhbvksbv svkjsbvksbvks sv shkv hskbvv shksbcsbcs chvchsvcs 
                                </p>
                            </div>
                        </div>
                    </div>
                   </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>