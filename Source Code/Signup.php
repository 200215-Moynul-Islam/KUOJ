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
                font-size: 30px;
                font-weight: normal;
                justify-content: center;
                
            }
            .Content .Logo .KU{
                color: #0074D9;
            }
            .Content .Logo .OJ{
                color: #224957;
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

            .Content .SigninSection{
                display: flex;
                align-items: center;
                font-size: 10px;
            }
            .Content .SigninSection .Message{
                margin-left: 67px;
            }
            .Content .SigninSection .Signin{
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
                $username=$_POST["Username"];
                $email=$_POST["Email"];
                $password=$_POST["Password"];
                $password=password_hash($password,PASSWORD_DEFAULT);

                //Checking whether the email already exist or not
                $sql="SELECT * FROM user_info WHERE email='$email'";
                $result=$connected->query($sql);
                $flag=TRUE;
                if($result->num_rows!=0){
                    $flag=FALSE;
                }else{
                    //SQL query to insert user data into the 'users' table
                    $sql="INSERT INTO user_info(username,email,user_password) VALUES ('$username','$email','$password')";
                    $result=$connected->query($sql);

                    //Checking whether the query was successful or not
                    if($result===TRUE){
                        echo "Account created successfully";
                        $_SESSION["Email"]=$email;//Storing the email in the session
                        //Redirecting to the home page
                        header("Location: http://localhost/PHP%20Code/Home.php");
                        exit;
                    }else{
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
                
                //Closing the database connection
                $connected->close();
            }
        ?>


        <div class="Content">
            <form method="post">
                <div class="Logo">
                    <span>Welcome to <span class="KU">KU</span><span class="OJ">OJ</span></span>
                </div>
                <input id="Username" type="text" name="Username" placeholder="Username" required></br>
                <input id="Email" type="email" name="Email" placeholder="E-mail" required></br>
                <input id="Password" type="password" name="Password" placeholder="Password" required></br>
                <button class="CreateButton" type="submit">Create Account</button>
                <div class="SigninSection">
                    <p class="Message">Already have an account?</p>
                    <nav class="Signin">
                        <a href="http://localhost/PHP%20Code/Login.php" class="Signin">Sign in</a>
                    </nav>
                </div>
                <div>
                    <p style="color:red; margin-left:60px;"><?php if($flag){echo "&nbsp";}else{echo "The email already exist";}?></p>
                </div>
            </form>
        </div>

        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>