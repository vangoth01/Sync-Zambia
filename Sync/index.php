<?php
session_start();
require_once 'core/init.php';  
$error = ""; //error display string
 if (isset($_SESSION['user_id'])){
       header("Location: Home.php");
   }
   else{
   if(isset($_POST) && !empty($_POST)){
       $email = mysqli_real_escape_string($connection, $_POST['email']);
       $password = $_POST['password'];
       
       if(empty($email) || empty($password)){//if inpus are not valid
           $error = "Invalid input, check your credentials";
       }
       else {//if inputs are valid
           try{
          $query = "select email,password,verified,business_name from users where email = '$email'";  //query   
          $result = mysqli_query($connection, $query);//execute query
          $countResult = mysqli_num_rows($result);//count rows that were affected
          
                if($countResult>0){ //record exists in database
                    $data = mysqli_fetch_array($result); //results from select query are stored in data variable
                        if(password_verify($password, $data['password'])){
                            //if entered password matches password in database
                            
                                if($data['verified']== 0){//if account not verified
                                    $error ="Please confirm your email first!";
                                }//end verified check
                                else{//if account is verified
                                 $_SESSION['user_id'] = $data['business_name'];//set session name to current user email    
                                 
                                 header("Location: Home.php"); //go to profile page
                               
                                 exit();
                                }
                        }//end password match
                        else{
                            //password not correct
                            $error ="Your Password Is Incorrect";
                            //echo "<script>alert('Your Password Is Incorrect')</script>";
                            
                                /*echo '<script type="text/javascript">';
                                echo 'setTimeout(function () { swal("ERROR!!","Phone number already exists in database!");';
                                echo '}, 1000);</script>';*/
                            
                        }//end password not correct
                }//end count for record check in database
                else{//password not in database
                    $error = "User Not In Our Database, Please Check Your Credentials!";
                }//end ps not in db
       }catch(Exception $ex){$error = $ex;}
            }//end else for valid inputs
   }//end post
 }//session else
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyNc | Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/login_styles.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
     <link href="https://fonts.googleapis.com/css?family=Arvo|Gugi|Patua+One|Pontano+Sans|Titan+One|Yanone+Kaffeesatz" rel="stylesheet">
</head>

<body style="background-image:url(&quot;assets/img/cover.jpg&quot;);">
    
   <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean-button" style="color:rgb(0,0,0);background-color:rgb(0,0,0);">
            <div class="container-fluid"><a class="navbar-brand" href="index.php" style="font-size:30px;color:rgb(255,255,255);">SyNc</a>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1" style="background: white"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div
                    class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav mr-auto"></ul><span class="navbar-text actions"> 
                        <a class="btn btn-light action-button" role="button" data-toggle="modal" data-target="#about_us_modal" style="margin-right: 10px">About Us</a>
                        <a class="btn btn-light action-button" role="button" href="register.php" style="background-color:#56c6c6;">Sign Up</a></span></div>
    </div>
    </nav>
    </div> 
    <div>
        <div class="container-fluid" style="margin-bottom:0px;">
            <div class="row" style="padding-bottom:0px;">
                <div class="col-md-1"></div>
                <div class="col-md-5" style="padding-right:100px;padding-left:100px;">
                    <h1 style="font-size:40px;color:rgb(255,255,255);padding-bottom:0px;">SyNc Zambia</h1>
                    <p class="lead" style="color:rgb(255,255,255);padding-bottom:15px;margin-bottom:15px;">Your Business is our priority</p>
                    <div><i class="icon-globe-alt" style="color:rgb(255,255,255);font-size:40px;"></i>
                        <p style="color:rgb(255,255,255);font-size:16px;padding-bottom:10px;margin-bottom:5px;">Show your business to the world</p>
                    </div><i class="icon-bubbles" style="color:rgb(255,255,255);font-size:40px;"></i>
                    <p style="font-size:14px;color:rgb(255,255,255);padding-bottom:10px;margin-bottom:5px;">Communicate with your clients</p><i class="icon-magnifier" style="font-size:41px;color:rgb(255,255,255);"></i>
                    <p style="color:rgb(255,255,255);font-size:15px;padding-right:0px;padding-bottom:10px;margin-bottom:5px;">Explore business</p><i class="icon-briefcase" style="font-size:40px;color:rgb(255,255,255);"></i>
                    <p style="color:rgb(255,255,255);margin-bottom:5px;">Find tenders and place your bids</p>
                </div>
                
                <div class="col-md-5 align-self-center" style="padding-bottom:10px;">
                    <h3 style="font-size:30px;color:rgb(255,255,255);padding-bottom:15px; padding-top: 10px">Get Started</h3>
                    
                   <div style="padding-top:15px" >
                   <?php if(isset($error)){?> <p style="color: aqua;text-align: center; font-weight: bolder"> <?php
                   echo $error ;?> </p>
                   <?php } ?>
                   </div>
                    <form role="form" method="post" style="margin-bottom:10px;">
                        <div><label style="color:rgb(255,255,255);font-size:17px;">Email</label></div>
                        <input type="text" name="email" required="" placeholder="Enter Email" 
                               style="width:300px;padding-bottom:0px;margin-bottom:30px;height:35px;"
                               value="<?php if(isset($email) & !empty($email)){echo $email;} ?>">
                    <div>
                        <label style="color:rgb(255,255,255);font-size:17px;">Password</label>
                    </div>
                        <input type="password" name="password" required="" placeholder="Password" 
                               minlength="6" style="width:300px;margin-bottom:12px;height:35px;">
                    <div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1" style="margin-bottom:33px;color:rgb(255,255,255);">Remember Me</label></div>
                    </div>
                    <div style="padding-bottom:0px;margin-bottom:0px;">
                        <button class="btn btn-info" type="submit" 
                                style="background-color:none;width:150px;margin-bottom:15px;">Login</button>
                    </div>
                   </form>
                        <a href="forgotPassword.php" style="color:rgb(255,255,255);font-weight: bolder">Forgot Password?</a>
                        <br>
                        <br>
                        <br>
                        <br>
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
                                
                                <p style="text-align:center; font-weight: normal">
                                    jjkfksbcsbjkss sbkshbcsc shbchsbcsbchjs  schscbsjhvcjsc svcjhvsjchvcvs sjvcjhsvcjhs-sssshbcsh chbvsjvhcsvc sh vhjsvhjsv svjh shvjhsv svh svjhs
                                     schcvjsvcjscjs csgjvcajgvjacv scjsvcjvsjcvs
                                    <br><br>
                                    svhbvhkbsv vshbvkhsbvhs vhbvkhsbvskv vkhsbvbshbvshbvs vsbvhjbsvvhsascsyuuyshcvjsc sv bjhvschjsvcsc csj cgshvchgsvchscc scgsvcghvsgsc scghsvcgsvgcs sgcvscvjS csghvc
                                    hvjhsvbjhbsvhjs vh jhvjhvhjcvscsh chvjhxchjcvjhxc chjvcjhxvjhxv
                                    <br><br>
                                    hsbcjsbcjs shsbhvbsbvss svj sbvkhsbvs svbskvhsbvksbv shvbhsbvshbvsv hsv skbvshkbvksbv svhs bvhsbvksbv svhsbvhsbvs vhsvbshvbshvb
                                   sv shkv hskbvv shksbcsbcs chvchsvcs hsghgcjsc chvcjhvchs schsvcjsvc shcvsjcshcjsv chvvhcjvcs hvchjvschsc chsvcjhvsc cjhvjvc chsvcjvsc jhvcjs
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