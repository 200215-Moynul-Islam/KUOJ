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
            .content .Message{
                width: 800px;
                height: 260px;
                background-color: #E5E5E5;
                border-radius: 10px;
            }
            .content .Message .MessageHeading{
                color: #14A8E8;
                font-size: 30px;
                font-weight: bold;
                text-align: center;
                margin-top: 15px;
                margin-bottom: 10px;
            }
            .content .Message p{
                margin: 10px;
                font-size: 15px;
                text-align: justify;
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
        ?>




        <header>
            <div class="logo">
                <span class="KU">KU</span><span class="OJ">OJ</span>
            </div>

            <nav>
                <a href="http://localhost/PHP%20Code/Home.php" style="color: #000000;">Home</a>
                <a href="http://localhost/PHP%20Code/Problems.php">Problems</a>
                <a href="http://localhost/PHP%20Code/Submit.php">Submit</a>
                <a href="http://localhost/PHP%20Code/Status.php">Status</a>
                <a href="http://localhost/PHP%20Code/Profile.php" class="profile">Profile</a>
                <a href="http://localhost/PHP%20Code/Logout.php" class="logout">Logout</a>
            </nav>
        </header>



        <div class="content">
            <div class="Message">
                <h3 class="MessageHeading">Welcome!</h3>
                <p>Welcome to Our Online Judge!</p>
                <p>Dear coders and problem solvers,</br>Welcome to our online judge platform - your coding sanctuary! Explore a diverse problem set, from warm-ups to mind-bending challenges, and unleash your problem-solving skills.</p>
                <p>Join our vibrant community, learn from peers, and track your progress. Let the coding adventure begin!</p>
                <p>Happy coding!</p>
                <p>Your coding partner</br>KU_ErrorMakers</p>
            </div>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>