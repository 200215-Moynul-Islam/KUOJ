<!DOCTYPE html>
<html>
    <head>
        <title>KUOJ</title>

        <style>
            body{
                margin:0;
                display: flex;
                flex-direction: column;
                min-height: 100vh;/*Ensure the page takes at least the full viewport height*/
                font-family: 'Inter', sans-serif;
            }



            header{
                background-color:#0F7CFB;
                display: flex;
                align-items: center;
            }

            .logo{
                display: flex;
                width: 140px;
                height: 60px;
                border-radius: 10px;
                background-color: #15D9D9;
                font-size: 50px;
                font-weight: bold;
                justify-content: center;
                
            }
            .logo .KU{
                color: #0074D9;
            }
            .logo .OJ{
                color: #224957;
            }

            nav{
                display: flex;
                align-items: center;
            }
            nav a{
                color: #FFFFFF;
                text-decoration: none;
                margin: 15px 2px 10px 2px;
                padding: 5px 10px 5px 10px;
                background-color: #14A8E8;
                border-radius: 5px;
            }
            nav a:hover{
                color: #000000;
            }
            .profile{
                position: absolute;
                right: 87px;
            }
            .logout{
                position: absolute;
                right: 10px;
            }



            .content{
                flex: 1;/*Allow the content to take available space*/
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .content .Box{
                display: flex;
                border-radius: 5px;
                background-color: #e5eeee;
            }
            .content .Box .UserInfo{
                margin: 0px 20px 0px 20px;
            }
            .content .ChangePassword{
                display: flex;
                border-radius: 5px;
                align-items: center;
                justify-content: center;
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

            //Checking if the "Remember Me" cookie exists
            if(isset($_COOKIE["remember_me"])){
                //Automatically loging in using the email from the cookie
                $email=$_COOKIE["remember_me"];//Accessing the email from the cookie
                $_SESSION["Email"]=$email;//Storing the email in the session
            }

            //Checking whether the user is logged in or not
            if(!isset($_SESSION['Email'])){
                //Session Timeout
                header("Location: http://localhost/PHP%20Code/Login.php");//Redirecting to Home page
                exit;
            }

            //function to get the username based on email stored in the session
            function getUsername($email,$connected){
                $sql="SELECT username FROM user_info WHERE email='$email'";
                $result=$connected->query($sql);
                if($result->num_rows==1){
                    $row=$result->fetch_assoc();
                    return $row['username'];
                }else{
                    return "Username retrival failed.";
                }
            }

            //function to get the number of problem solved
            function solvedCount($email,$connected){
                $email=$_SESSION["Email"];
                $sql="SELECT DISTINCT email,problem_id FROM user_info natural join submission WHERE verdict='Accepted' and email='$email'";
                $result=$connected->query($sql);
                return $result->num_rows;
            }

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

            $Email=$_SESSION['Email'];
            $Username=getUsername($Email,$connected);
            $solved=solvedCount($Email,$connected);


            //Closing the database connection
            $connected->close();
        ?>





        <header>
            <div class="logo">
                <span class="KU">KU</span><span class="OJ">OJ</span>
            </div>

            <nav>
                <a href="http://localhost/PHP%20Code/Home.php">Home</a>
                <a href="http://localhost/PHP%20Code/Problems.php">Problems</a>
                <a href="http://localhost/PHP%20Code/Submit.php">Submit</a>
                <a href="http://localhost/PHP%20Code/Status.php">Status</a>
                <a href="http://localhost/PHP%20Code/Profile.php" style="color: #000000;" class="profile">Profile</a>
                <a href="http://localhost/PHP%20Code/Logout.php" class="logout">Logout</a>
            </nav>
        </header>



        <div class="content">
            <main>
                <div class="Box">
                    <div class="UserInfo">
                        <p>Username: <?php echo $Username; ?></p>
                        <p>E-mail: <?php echo $Email; ?></p>
                        <p>Solved Count: <?php echo $solved; ?></p>
                    </div>
                </div>
                <div class="ChangePassword">
                    <nav>
                        <a href="http://localhost/PHP%20Code/UpdatePassword.php">Update Password</a>
                    </nav>
                </div>
            </main>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>