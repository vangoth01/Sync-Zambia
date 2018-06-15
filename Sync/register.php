<?php
    
require_once('core/init.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


    if(isset($_POST) & !empty($_POST)){
    //if post is set and post is not empty
        
     $errorMessage = NULL;//set error message to empty
     
     //get values from input field and escape characters
     $email = mysqli_real_escape_string($connection, $_POST['email']);
     $business_name = mysqli_real_escape_string($connection, $_POST['business_name']);
     $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);
     $password = $_POST['password'];
     $passwordConfirm = $_POST['passwordConfirm'];
     
     //email token generation
     $email_token = password_hash(rand(110,999999999 ), PASSWORD_DEFAULT);
     
     //phone number plus country code since user only enters numbers beging 09
     $phone_country_code = "+26";
     $phone_plus_code = $phone_country_code.$phone_number;
   
     
     //hash password
     $hashed_password = password_hash($password,PASSWORD_DEFAULT);
    
     //password confirmation
   
     
     if(password_verify($passwordConfirm, $hashed_password)){  //if password matches
   
    // check if email already exists in database
    $emailExist = "select * from users where email ='$email' ";
    $emailCheckResults = mysqli_query($connection, $emailExist) ;   
    $countMail = mysqli_num_rows($emailCheckResults);
    
    if ($countMail == 1){//email exists
    //show error message  
    /*echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("ERROR!!","Email already exists in database!");';
    echo '}, 1000);</script>';*/
        $errorMessage = "Entered Email Already Exists In Database. Try Another One";
        
    }//end email exist check
     
   
    
    // check if bussiness name already exists in database
    $business_nameExist = "select * from users where business_name ='$business_name' ";
    $business_nameExistResults = mysqli_query($connection, $business_nameExist) ;   
    $countBn = mysqli_num_rows($business_nameExistResults);
    
    if ($countBn == 1){//exists
    /*echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("ERROR!!","Business Name in use Please use another one");';
    echo '}, 1000);</script>';*/
        $errorMessage = "Sorry! That Business Name Is Taken. Please Try Another One";
    }//end business name exist check
    
  
    // check if phone number already exists in database
    $phoneExist = "select * from users where phone_number ='$phone_plus_code' ";
    $phoneExistResults = mysqli_query($connection, $phoneExist) ;   
    $countPn = mysqli_num_rows($phoneExistResults);
    
    if ($countPn == 1){//if a result is returned from database
    /*echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("ERROR!!","Phone number already exists in database!");';
    echo '}, 1000);</script>';*/
        $errorMessage = "That Phone Number Is Tied To Another Business. Please Try Another One";
    }//end phone exist check
    
    //everything else is okay credential wise
    //prepare sql statement to insert into database
    
    try{
      
     $sql_query = "INSERT INTO users(email,business_name,phone_number,password,joined,email_token) VALUES ('$email','$business_name','$phone_plus_code','$hashed_password', now(),'$email_token')";
     
    //insert into database
     $result = mysqli_query($connection, $sql_query); 
     
    }//end try
    catch (mysqli_sql_exception $insertEx){
        $errorMessage = $insertEx; //show error message
    }//end catch
    
     
     
     
     if($result){//if inserted into database
         
        $mail = new PHPMailer(true); 
        try{
         //send confirmation email
         $mail -> SMTPDebug = 0;
         $mail -> isSMTP();
         $mail -> Host = 'smtp.gmail.com';
         $mail -> Port = 587;
        // $mail -> SMTPSecure = 'ssl';
         $mail -> SMTPAuth = true;
         $mail -> Username = 'blacksylar01@gmail.com';
         $mail -> Password = 'jevillain01';
         
         $mail -> From='blacksylar01@gmail.com';
         $mail -> FromName = 'Sync Zambia';
         
         $mail ->smtpConnect(
                    array(
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                            "allow_self_signed" => true
                        )
                    )
                 );
         
         $mail ->addAddress($_POST['email']);
         $mail ->Subject ="Sync: Sign Up Email verification";
         $mail ->isHTML(TRUE);
         $mail -> Body = ""
                 . "<p>Thank you for signing up, Your account has been created!"
                 . "<br>"
                 . "Please click link below to verify your account"
                 . "<br>"
                 . "<a href='http://localhost/NewSyncLogin/confirmEmail.php?email=$email&email_token=$email_token'>Click here!!!</a></p>";
         
        //check if confirmation email is sent 
         if ($mail->send()){
             header("Location: http://localhost/NewSyncLogin/registerSuccess.php");
             exit;
          }//end send check
        }//end try 
        catch (Exception $ex) {
            
           $errorMessage = 'Mailer error:'.$mail->ErrorInfo;
        }//end catch
             
        
     }//end if user has been inserted in database
     
     }//end password verify
     else{
         $errorMessage = 'Passwords did not match. Please try again!';
     }//end else
     
    }//end post
    
   
?>
<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyNc | Register</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/register_styles.css">
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
    
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div style="height:160px;font-size:100px;"><i class="icon-user-follow" style="height:auto;width:auto;font-size:100px;margin-bottom:20px;color:rgb(255,255,255);"></i></div>
                    <div>
                        <p style="color:rgb(255,255,255);font-size:20px;">Create New Account</p>
                    </div>
                </div>
                
                <div class="col-md-6" style="padding-bottom:30px;">
                   
                   <div style="padding-top:20px" >
                   <?php if(isset($errorMessage)){?> <p style="color: aqua;text-align: center; font-weight: bolder"> <?php
                   echo $errorMessage ;?> </p>
                   <?php } ?>
                   </div>
                    
                    <form role="form" method="post" class="form-group">
                    
                    <div> 
                    <label>Email</label></div>
                    <input type="email" name="email" required placeholder="Enter Your Email" inputmode="email" 
                            value="<?php if(isset($email) & !empty($email)){echo $email;} ?>">
                    
                    <div>
                        <label>Business Name</label></div>
                        <input type="text" name="business_name" required="" placeholder="Provide Your Business Name" 
                                value="<?php if(isset($business_name) & !empty($business_name)){echo $business_name;} ?>">
                    <div>
                        <label>Phone</label></div>
                        <input type="tel" required="" name="phone_number" placeholder="Phone Number (Begin 09X)" maxlength="10" minlength="10"
                                value="<?php if(isset($phone_number) & !empty($phone_number)){echo $phone_number;} ?>">
                    <div>
                        <label>Password</label></div>
                        <input type="password" name="password" required="" placeholder="Choose Your Password">
                    <div>
                        <label>Confirm Password</label></div>
                        <input type="password" name="passwordConfirm" placeholder="Confirm Your Password" >
                    <div>
                        <button class="btn btn-info" type="submit" style="width:150px;">Sign Up</button></div>
                    </form>
                </div>
                </div>
                
            </div>
        </div>
    </div>
    <!--
    <nav class="fixed-bottom navbar-dark bg-dark" style="margin-bottom: 0px; padding-bottom: 0px; color: black; text-align: center">
          <p style="font-size: 15px; color: whitesmoke;font-weight: normal;font-family:'Pontano Sans', sans-serif;padding-top: 20px">
             SyNc Zambia Copyright © 2018
          </p>
         
    </nav>
    -->
    
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