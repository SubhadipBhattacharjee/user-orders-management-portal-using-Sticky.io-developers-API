<?php 
if(session_status() === PHP_SESSION_NONE) session_start(); // Start session if not already started
require_once __DIR__. '/controllers/Auth.php'; 

$auth = new Auth();
if(!$auth->isLoggedIn()){
    header("Location: index.php");
    exit();
}else{
    $auth->logout(); 
    // Redirect to the login page after logout
    header("Location: index.php");
    exit();
}




?>