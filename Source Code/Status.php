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
                justify-content: center;
            }
            .content main{
                font-size: 15px;
                margin-top: 20px;
                width: 1000px;
            }
            .content .Time{
                width: 300px;
                height: 40px;
                font-weight: 500;
                border-radius: 5px 0px 0px 0px;
                background-color: #75ffa5;
            }
            .content .Date{
                width: 400px;
                height: 40px;
                font-weight: 500;
                background-color: #75ffa5;
            }.content .Problem{
                width: 500px;
                height: 40px;
                font-weight: 500;
                background-color: #75ffa5;
            }
            .content .Verdict{
                width: 300px;
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

            $email=$_SESSION["Email"];
            $sql="SELECT shomoy,problem_name,verdict,href FROM submission NATURAL JOIN problem WHERE email='$email' ORDER BY shomoy DESC";
            $result=$connected->query($sql);
        ?>

        <header>
            <div class="logo">
                <span class="KU">KU</span><span class="OJ">OJ</span>
            </div>

            <nav>
                <a href="http://localhost/PHP%20Code/Home.php">Home</a>
                <a href="http://localhost/PHP%20Code/Problems.php">Problems</a>
                <a href="http://localhost/PHP%20Code/Submit.php">Submit</a>
                <a href="http://localhost/PHP%20Code/Status.php" style="color: #000000;">Status</a>
                <a href="http://localhost/PHP%20Code/Profile.php" class="profile">Profile</a>
                <a href="http://localhost/PHP%20Code/Logout.php" class="logout">Logout</a>
            </nav>
        </header>



        <div class="content">
            <main>
                <table>
                    <thead>
                        <tr>
                            <th class="Time">Time</th>
                            <th class="Date">Date</th>
                            <th class="Problem">Problem</th>
                            <th class="Verdict">Verdict</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row):?>
                            <tr>
                                <?php
                                    $link=$row["href"];
                                ?>
                                <td class="FirstColumn"><?php echo substr($row["shomoy"],11,8);?></td>
                                <td class="SecondColumn"><?php echo substr($row["shomoy"],0,10);?></td>
                                <td class="ThirdCoulumn"><a href="<?php echo $link;?>"><?php echo $row['problem_name'];?></a></td>
                                <th class="FourthColumn"><?php echo $row["verdict"];?></th>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </main>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>