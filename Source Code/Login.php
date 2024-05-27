<!DOCTYPE html>
<html>
    <head>
        <title>KUOJ</title>

        <style>
            body{
                margin: 0;
                display: flex;
                flex-direction: column;
                min-height: 100vh;/*Ensure the page takes at least the full viewport height*/
                font-family: 'Inter', sans-serif;
            }

            
            .Content{
                flex: 1;/*Allow the content to take available space*/
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .Content .Logo{
                display: flex;
                background-color: #ffffff;
                font-size: 55px;
                font-weight: normal;
                justify-content: center;
                
            }
            .Content .Logo .KU{
                color: #0074D9;
            }
            .Content .Logo .OJ{
                color: #224957;
            }

            .Content #Email{
                width: 300px;
                height: 30px;
                background-color: #224957;
                font-size: 15px;
                color: #ffffff;
                font-weight: bold;
                text-indent: 10px;
                border-radius: 10px;
                margin-top: 5px;
            }

            .Content #Password{
                width: 300px;
                height: 30px;
                background-color: #224957;
                font-size: 15px;
                color: #ffffff;
                font-weight: bold;
                text-indent: 10px;
                border-radius: 10px;
                margin-top: 5px;
                margin-bottom: 5px;
            }

            .Content #RememberMe{
                width: 15px;
                height: 15px;
            }
            .Content label[for="RememberMe"]{
                font-size: 14px;
            }
            .Content #RememberMe,
            label[for="RememberMe"],
            nav{
                display: inline-block;
                vertical-align: middle;
            }

            .Content .ForgotPassword{
                margin-left: 57px;
                color: #224957;
                font-size: 14px;
            }

            .Content .LoginButton{
                width: 305px;
                height: 30px;
                margin-top: 5px;
                background-color: #224957;
                color: #ffffff;
                border-radius: 10px;
                font-size: 15px;
                font-weight: bold;
            }
            .Content .LoginButton:hover{
                background-color: #0F7CFB;
            }

            .Content .SignupSection{
                display: flex;
                align-items: center;
                font-size: 10px;
            }
            .Content .SignupSection .Message{
                margin-left: 70px;
            }
            .Content .SignupSection .Signup{
                margin-left: 5px;
            }


            .footer{
                background-color: #0F7CFB;
                color: white;
                font-size: 10px;
                text-align: center;
            }
        </style>

        <!--To use the font Inter-->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php
            session_start();//Starting a PHP session
            $flag=TRUE;

            //Checking if the "Remember Me" cookie exists
            if(isset($_COOKIE["remember_me"])){
                //Automatically loging in using the email from the cookie
                $email=$_COOKIE["remember_me"];//Accessing the email from the cookie
                $_SESSION["Email"]=$email;//Storing the email in the session
                header("Location: http://localhost/PHP%20Code/Home.php");//Redirecting to Home page
                exit;
            }

            //Checking whether the form is submitted or not
            if($_SERVER["REQUEST_METHOD"]=="POST"){
                //Database configuration
                $mysql_servername="localhost";
                $mysql_username="root";
                $mysql_password="";
                $dbname="kuoj";

                //Creating a database connection
                $connected=new mysqli($mysql_servername,$mysql_username,$mysql_password,$dbname);

                //Checking connection
                if($connected->connect_error){
                    die("Connection failed: ".$connected->connect_error);
                }

                //Taking input from the form
                $email=$_POST["Email"];
                $password=$_POST["Password"];

                //SQL query to validate the account
                $sql="SELECT * FROM user_info WHERE email='$email'";
                $result=$connected->query($sql);

                //Checking whether the account is valid or not
                if($result->num_rows==1){
                    $row=$result->fetch_assoc();
                    $retrievedPassword=$row['user_password'];
                }
                if($result->num_rows==1&&password_verify($password,$retrievedPassword)){
                    //Login successful
                    $_SESSION["Email"]=$email;//Storing the email in the session
                    //Checking if the "Remember Me" checkbox is checked or not
                    if(isset($_POST["RememberMe"])){
                        //Setting a cookie with the user's email that expires in 30 days
                        setcookie("remember_me",$email,time()+(86400*30),"/");
                    }
                    header("Location: http://localhost/PHP%20Code/Home.php");//Redirecting to Home page
                    exit;
                }else{
                    //log in failded
                    $flag=FALSE;
                }

                //Closing the database connection
                $connected->close();
            }
        ?>


        <div class="Content">
            <form method="post">
                <div class="Logo">
                    <span class="KU">KU</span><span class="OJ">OJ</span>
                </div>
                <input id="Email" type="email" name="Email" placeholder="E-mail" required></br>
                <input id="Password" type="password" name="Password" placeholder="Password" required></br>
                <input id="RememberMe" type="checkbox" name="RememberMe">
                <label for="RememberMe">Remember me</label>
                <nav>
                    <a href="http://localhost/PHP%20Code/ForgotPassword.php" class="ForgotPassword">Forgot password?</a>
                </nav></br>
                <button class="LoginButton">Login</button>
                <div class="SignupSection">
                    <p class="Message">Don't have an account?</p>
                    <nav class="Signup">
                        <a href="http://localhost/PHP%20Code/Signup.php" class="Signup">Sign up</a>
                    </nav>
                </div>
                <div>
                    <p style="color:red; margin-left:50px;"><?php if($flag){echo "&nbsp";}else{echo "Invalid Email or Password";}?><p>
                </div>
            </form>
        </div>

        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>