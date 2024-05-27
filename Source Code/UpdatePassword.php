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



            .Content{
                flex: 1;/*Allow the content to take available space*/
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .Content #Username{
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

            .Content .CreateButton{
                width: 150px;
                height: 30px;
                margin-left: 75px;
                background-color: #224957;
                color: #ffffff;
                border-radius: 10px;
                font-size: 15px;
                font-weight: bold;
            }
            .Content .CreateButton:hover{
                background-color: #0F7CFB;
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

                $email=$_SESSION["Email"];
                //Taking input from the form
                $password=$_POST["Password"];
                $password=password_hash($password,PASSWORD_DEFAULT);

                //SQL query to update the password into the 'users' table
                $sql="UPDATE user_info SET user_password='$password' WHERE email='$email'";
                $result=$connected->query($sql);

                //Checking whether the query was successful or not
                if($result===TRUE){
                    echo "Account created successfully";
                    $_SESSION["Email"]=$email;//Storing the email in the session
                    //Redirecting to the home page
                    header("Location: http://localhost/PHP%20Code/Profile.php");
                    exit;
                }else{
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                
                //Closing the database connection
                $connected->close();
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



        <div class="Content">
            <form method="post">
                <input id="Password" type="password" name="Password" placeholder="New Password" required></br>
                <button class="CreateButton" type="submit">Update</button>
            </form>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>