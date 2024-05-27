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
                width: 1000px;
            }
            .content .Heading{
                text-align: center;
                line-height: 0.5;
            }
            .content .statement{
                text-align: justify;
            }
            .content .input{
                text-align: justify;
            }
            .content .output{
                text-align: justify;
            }
            .content .sample{
                text-align: justify;
            }
            .content .explanation{
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

            $sql="SELECT problem_name,time_limit,memory_limit,statement,input,output,explanation FROM problem where problem_id=10";
            $result=$connected->query($sql);
            $row=$result->fetch_assoc();
            $exp=$row["explanation"];
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
            <main>
                <div class="Heading">
                    <p style="font-size: 30px; font-weight: 50; line-height: 0;"><?php echo $row["problem_name"]?></p>
                    <p>Time Limit: <?php echo $row["time_limit"]?> second</p>
                    <p>Memory Limit: <?php echo $row["memory_limit"]?> MB</p>
                </div>

                <div class="statement">
                    <p><?php echo $row["statement"]?></p>
                </div>

                <div class="input">
                    <p><span style="font-size: 15px; font-weight: bold;">Input</span></br><?php echo $row["input"]?></p>
                </div>

                <div class="output">
                    <p><span style="font-size: 15px; font-weight: bold;">Output</span></br><?php echo $row["output"]?></p>
                </div>

                <div class="sample">
                    <?php
                        $sql="SELECT sample_input,sample_output FROM sample where problem_id=10";
                        $result=$connected->query($sql);
                    ?>
                    <span style="font-size: 15px; font-weight: bold;">Example</span></br>
                    <?php foreach($result as $row):?>
                        <p>
                            <span style="font-size: 13px; font-weight: bold;">Input</span></br>
                            <span><?php echo $row["sample_input"];?></span></br>
                            <span style="font-size: 13px; font-weight: bold;">Output</span></br>
                            <span><?php echo $row["sample_output"];?></span></br>
                        </p>
                    <?php endforeach;?>
                </div>

                <div class="explanation">
                    <p><span style="font-size: 15px; font-weight: bold;">Explanation</span></br><?php echo $exp?></p>
                </div>
            </main>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>