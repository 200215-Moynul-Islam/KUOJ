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
            .content form{
                font-size: 15px;
                margin-top: 20px;
                width: 1000px;
            }

            .content #ProblemID{
                width: 42px;
            }

            .content .CodeContainer{
                display: flex;
                align-items: center;
            }
            .content .CodeContainer textarea{
                width: 800px;
                height: 480px;
                resize: none;
            }


            .content .SubmitButtonContainer{
                display: flex;
                justify-content: center;
            }
            .content .SubmitButtonContainer .SubmitButton{
                width: 70px;
                height: 30px;
                margin-top: 5px;
                background-color: #224957;
                color: #ffffff;
                border-radius: 10px;
                font-size: 15px;
                font-weight: bold;
            }
            .content .SubmitButtonContainer .SubmitButton:hover{
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
                $email=$_SESSION["Email"];
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

                $cppCode=$_POST["Source_Code"];
                // echo $cppCode;
                $problem_ID=$_POST["ProblemID"];
                $time=date("Y-m-d H:i:sa");

                $cppFile = 'user_code.cpp';
                file_put_contents($cppFile, $cppCode);
                $outputFile = 'compiled_code.exe';
                $compileCommand = "g++ $cppFile -o $outputFile 2>&1";
                $compileOutput = shell_exec($compileCommand);

                if (!$compileOutput) {
                    $sql="SELECT testing_code FROM problem where problem_ID='$problem_ID'";
                    $result=$connected->query($sql);
                    foreach($result as $row){
                        $testCode=$row["testing_code"];
                        // echo $testCode;
                    }
                    $testFile = 'test_code.cpp';
                    file_put_contents($testFile, $testCode);
                    $testoutputFile = 'compiled_test_code.exe';
                    $compileCommand = "g++ $testFile -o $testoutputFile 2>&1";
                    $compileOutput = shell_exec($compileCommand);

                    $output;

                    $sql="SELECT case_input FROM test_case Where problem_id='$problem_ID' order by case_no";
                    $result=$connected->query($sql);
                    foreach ($result as $row){
                        $case_input=$row["case_input"];
                        $input_file = 'input.txt';
                        file_put_contents($input_file, $case_input);
                        $output = shell_exec('compiled_code.exe');
                        $ans_file='ans.txt';
                        file_put_contents($ans_file,$output);
                        $output=shell_exec('compiled_test_code.exe');
                        if($output!="ACCEPTED"){
                            break;
                        }
                    }
                    $sql="insert into submission (email,problem_id,verdict,shomoy) values('$email', '$problem_ID', '$output','$time')";
                    $result=$connected->query($sql);
                    unlink($output);
                    unlink($testoutputFile);
                    unlink($testFile);
                    unlink($ans_file);
                    unlink($input_file);
                } else {
                    $sql="insert into submission (email,problem_id,verdict,shomoy) values('$email', '$problem_ID', 'Compilation Error','$time')";
                    $result=$connected->query($sql);
                }
                unlink($cppFile);
                unlink($outputFile);
                
                $connected->close();
                header("Location: http://localhost/PHP%20Code/Status.php");
                exit;
            }
        ?>

        <header>
            <div class="logo">
                <span class="KU">KU</span><span class="OJ">OJ</span>
            </div>

            <nav>
                <a href="http://localhost/PHP%20Code/Home.php">Home</a>
                <a href="http://localhost/PHP%20Code/Problems.php">Problems</a>
                <a href="http://localhost/PHP%20Code/Submit.php" style="color: #000000;">Submit</a>
                <a href="http://localhost/PHP%20Code/Status.php">Status</a>
                <a href="http://localhost/PHP%20Code/Profile.php" class="profile">Profile</a>
                <a href="http://localhost/PHP%20Code/Logout.php" class="logout">Logout</a>
            </nav>
        </header>



        <div class="content">
            <form method="post">
                <label for="ProblemID">Problem ID:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <input id="ProblemID" name="ProblemID" type="number" placeholder="" required min="1" max="10"></br>
                
                <label for="Language">&nbsp;Language:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <select id="Language" name="Language">
                    <!-- <option value="1">C</option> -->
                    <option value="1">C++</option>
                </select></br></br>

                <div class="CodeContainer">
                    <label for="Source_Code">&nbsp;Source Code:&nbsp;</label>
                    <textarea id="Source_Code" name="Source_Code" placeholder="//Write your code here" class="Source_Code"></textarea>
                </div>

                <div class="SubmitButtonContainer">
                    <button class="SubmitButton">Submit</button>
                </div>
            </form>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>