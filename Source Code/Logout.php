<?php
    session_start();//Starting a session
    session_unset();//Clearing the session variable
    session_destroy();//Destroying the session

    if(isset($_COOKIE['remember_me'])){
        //Setting the cookie to expire in the past to delete it
        setcookie('remember_me','',time()-3600,'/');
    }
    header('Location: http://localhost/PHP%20Code/login.php');//Redirecting to the login page
    exit;
?>