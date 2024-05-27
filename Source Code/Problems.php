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

            header nav{
                display: flex;
                align-items: center;
            }
            header nav a{
                color: #FFFFFF;
                text-decoration: none;
                margin: 15px 2px 10px 2px;
                padding: 5px 10px 5px 10px;
                background-color: #14A8E8;
                border-radius: 5px;
            }
            header nav a:hover{
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
                justify-content: center;
            }
            .content .ProblemNo{
                width: 200px;
                height: 40px;
                font-weight: 500;
                border-radius: 5px 0px 0px 0px;
                background-color: #75ffa5;
            }
            .content .ProblemName{
                width: 600px;
                height: 40px;
                font-weight: 500;
                background-color: #75ffa5;
            }
            .content .Status{
                width: 200px;
                height: 40px;
                font-weight: 500;
                border-radius: 0px 5px 0px 0px;
                background-color: #75ffa5;
            }
            .content tbody tr{
                height: 40px;
                text-align: center;
                font-weight: 100;
                background-color: #e5eeee;
            }

            .content tbody tr .ThirdColumn{
                font-weight: 100;
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

            $sql="SELECT problem_id,problem_name,href FROM problem";
            $result=$connected->query($sql);
        ?>


        <header>
            <div class="logo">
                <span class="KU">KU</span><span class="OJ">OJ</span>
            </div>

            <nav>
                <a href="http://localhost/PHP%20Code/Home.php">Home</a>
                <a href="http://localhost/PHP%20Code/Problems.php" style="color: #000000;">Problems</a>
                <a href="http://localhost/PHP%20Code/Submit.php">Submit</a>
                <a href="http://localhost/PHP%20Code/Status.php">Status</a>
                <a href="http://localhost/PHP%20Code/Profile.php" class="profile">Profile</a>
                <a href="http://localhost/PHP%20Code/Logout.php" class="logout">Logout</a>
            </nav>
        </header>



        <div class="content">
            <main style="margin-top: 20px;">
                <table>
                    <thead>
                        <tr>
                            <th class="ProblemNo">Problem No.</th>
                            <th class="ProblemName">Problem Name</th>
                            <th class="Status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <?php 
                                    $link=$row["href"];
                                ?>
                                <td class="FirstColumn"><?php echo $row['problem_id']; ?></td>
                                <td class="SecondColumn"><nav><a href="<?php echo $link; ?>"><?php echo $row['problem_name']; ?></a></nav></td>
                                <th class="ThirdColumn">
                                    <?php
                                        $Email=$_SESSION["Email"];
                                        $Problem=$row['problem_id'];
                                        $sql="SELECT problem_id FROM submission where verdict='Accepted' and email='$Email' and problem_id='$Problem' ";
                                        $result=$connected->query($sql);
                                        if($result->num_rows>=1) {
                                            echo "Solved";
                                        }else{
                                            echo "Unsolved";
                                        }
                                    ?>
                                </th>
                            </tr>
                        <?php endforeach; ?>
                            <!-- //Closing the database connection -->
                        <?php
                            $connected->close();
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>