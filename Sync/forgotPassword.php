<?php



require_once('core/init.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';  

function redirect($url) {
    if(!headers_sent()){
        header('Location: '.$url);
        exit;
    }//end not header sent
    else{
       echo '<script type="text/javascript">'; 
       echo 'window.location.href="'.$url.'";';
       echo '</script>';
       echo '<noscript>';
       echo 'meta http-equiv="refresh" conent="0;url='.$url.'" />';
       echo '</noscript>'; exit;
    }
}//end function
    if(isset($_POST['email']) & !empty($_POST['email'])){
        
       $email = mysqli_real_escape_string($connection, $_POST['email']);
       
       $query = "select * from users where email='$email'";
       $sql = mysqli_query($connection, $query);
       $count = mysqli_num_rows($sql);
       if($count > 0){
           //create email token
           $email_token = password_hash(rand(110,999999999 ), PASSWORD_DEFAULT);
           //update users add token and token expiry
           
         
           $query = "update users set email_token='$email_token',"
                   . "email_token_expire = date_add(now(),interval 5 minute)"
                   . "where email = '$email'";
        try{  
        $result = mysqli_query($connection, $query);
        
        if($result){
        
        $mail = new PHPMailer(true); 
        
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
         $mail ->Subject ="Sync: Password Recovery";
         $mail ->isHTML(TRUE);
         $mail -> Body = "<p>Hi,</p><br>"
                 . "<p>You are one step away from recovering your account"
                 . "<br>"
                 . "Please click link below to reset your account password"
                 . "<br><br>"
                 . "<a href='http://localhost/NewSyncLogin/resetPassword.php?email=$email&email_token=$email_token'>Click here to reset your password</a></p>";
         
        //check if email is sent 
         if (!$mail->send()){
             
            echo  "Error: ".$mail->ErrorInfo; 
            
          }//end mail not sent check
          else{//mail sent
              redirect("registerSuccess.php");
              
          }
        }//end result returned
       
        }//end try
       catch (Exception $ex) {
            
           $errorMessage = 'Mailer error:'.$mail->ErrorInfo;
        }
        }//end count
        else {
            $errorMessage = "Email Does Not Exist in database, please try again";
           //exit(json_encode(array("status" => 0,"msg" => 'Email Does Not Exist In Database')));
       }
       
       
    }//end post

?>
<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyNc | Forgot Password</title>
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
    
     <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center">
                    <div style="padding-top:100px;padding-bottom:40px;">
                            
                      <i class="icon-lock" style="color:rgb(255,255,255);font-size:100px;"></i>
                  
                        <form method="post">
                            <div style="padding-top: 20px">
                                <label style="margin-bottom:20px;">Enter Your Email To Rest Forgotten Password</label>
                            </div>
                        
                            <div>
                                <?php if(isset($errorMessage)){?> <p style="color: aqua;text-align: center; font-weight: bolder"> <?php
                                echo $errorMessage ;?> </p>
                                <?php } ?>
                                </div>
                            <p style="font-weight: bolder" id="response">
                            </p>
                        <input type="email" name="email" placeholder="Enter Your Email" style="width:300px;margin-top:10px;height:35px;">
                        <div>
                            <button class="btn btn-info" type="submit" style="width:150px;">Submit</button>
                        </div>
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