<?php


//database connection
$connection = mysqli_connect(
        "localhost",
        "root",
        "");
//error handling
if(!$connection){
    die(mysqli_error($connection)); //kill
}

//select database 
$db_select = mysqli_select_db($connection, "sync_login");
if(!$db_select){
    die(mysqli_error($connection)); //kill
}

?>
