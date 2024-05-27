<!DOCTYPE html>
<html>
<head>
    <title>KUOJ</title>

    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the page takes at least the full viewport height */
            font-family: 'Inter', sans-serif;
        }

        header {
            background-color: #0F7CFB;
            display: flex;
            align-items: center;
        }

        .logo {
            display: flex;
            width: 140px;
            height: 60px;
            border-radius: 10px;
            background-color: #15D9D9;
            font-size: 50px;
            font-weight: bold;
            justify-content: center;
        }

        .logo .KU {
            color: #0074D9;
        }

        .logo .OJ {
            color: #224957;
        }

        nav {
            display: flex;
            align-items: center;
        }

        nav a {
            color: #FFFFFF;
            text-decoration: none;
            margin: 15px 2px 10px 2px;
            padding: 5px 10px 5px 10px;
            background-color: #14A8E8;
            border-radius: 5px;
        }

        nav a:hover {
            color: #000000;
        }

        .profile {
            position: absolute;
            right: 87px;
        }

        .logout {
            position: absolute;
            right: 10px;
        }

        .content {
            flex: 1; /* Allow the content to take available space */
            display: flex;
            justify-content: center;
        }

        .content form {
            font-size: 15px;
            margin-top: 20px;
            width: 1000px;
        }

        .content #ProblemID {
            width: 42px;
        }

        .content .CodeContainer {
            display: flex;
            align-items: center;
        }

        .content .CodeContainer textarea {
            width: 800px;
            height: 480px;
            resize: none;
        }

        .content .SubmitButtonContainer {
            display: flex;
            justify-content: center;
        }

        .content .SubmitButtonContainer .SubmitButton {
            width: 70px;
            height: 30px;
            margin-top: 5px;
            background-color: #224957;
            color: #ffffff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: bold;
        }

        .content .SubmitButtonContainer .SubmitButton:hover {
            background-color: #0F7CFB;
        }

        .footer {
            background-color: #0F7CFB;
            color: white;
            font-size: 10px;
            text-align: center;
        }
    </style>

    <!-- To use the font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
<?php
session_start(); // Starting a PHP session

// Checking if the "Remember Me" cookie exists
if (isset($_COOKIE["remember_me"])) {
    // Automatically log in using the email from the cookie
    $email = $_COOKIE["remember_me"]; // Accessing the email from the cookie
    $_SESSION["Email"] = $email; // Storing the email in the session
}

// Checking whether the user is logged in or not
if (!isset($_SESSION['Email'])) {
    // Session Timeout
    header("Location: http://localhost/PHP%20Code/Login.php"); // Redirecting to Home page
    exit;
}

// Checking whether the form is submitted or not
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION["Email"];
    // Database configuration
    $mysql_servername = "localhost";
    $mysql_username = "root";
    $mysql_password = "";
    $dbname = "kuoj";

    // Creating a database connection
    $connected = new mysqli($mysql_servername, $mysql_username, $mysql_password, $dbname);

    // Checking connection
    if ($connected->connect_error) {
        die("Connection failed: " . $connected->connect_error);
    }

    $cppCode = $_POST["Source_Code"];
    $problem_ID = $_POST["ProblemID"];
    $time = date("Y-m-d H:i:sa");

    $cppFile = 'user_code.cpp';
    file_put_contents($cppFile, $cppCode);
    $outputFile = 'compiled_code.exe';
    $compileCommand = "g++ $cppFile -o $outputFile 2>&1";
    $compileOutput = shell_exec($compileCommand);

    if (!$compileOutput) {
        $sql = "SELECT testing_code,time_limit,memory_limit FROM problem where problem_ID='$problem_ID'";
        $result = $connected->query($sql);
        foreach ($result as $row) {
            $testCode = $row["testing_code"];
            $timelimit = $row["time_limit"];
            $memorylimit = $row["memory_limit"];
        }
        $testFile = 'test_code.cpp';
        file_put_contents($testFile, $testCode);
        $testoutputFile = 'compiled_test_code.exe';
        $compileCommand = "g++ $testFile -o $testoutputFile 2>&1";
        $compileOutput = shell_exec($compileCommand);

        $output = '';

        $sql = "SELECT case_input FROM test_case order by case_no";
        $result = $connected->query($sql);
        foreach ($result as $row) {
            $process = proc_open('./compiled_code.exe', [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ], $pipes);
            if (is_resource($process)) {
                // Set resource limits using ini_set()
                $timeLimitInSeconds = $timelimit; // Assuming $timelimit is in seconds
                $memoryLimitInBytes = $memorylimit * 1024 * 1024; // Assuming $memorylimit is in MB

                ini_set('max_execution_time', $timeLimitInSeconds);
                ini_set('memory_limit', $memoryLimitInBytes);
                $startTime = microtime(true);
                $startMemory = memory_get_usage();
                // Write input data to the process
                fwrite($pipes[0], $row['case_input']);
                fclose($pipes[0]);

                // Capture program output and errors
                $output = stream_get_contents($pipes[1]);
                $errors = stream_get_contents($pipes[2]);

                // Close the output and error streams
                fclose($pipes[1]);
                fclose($pipes[2]);

                // Close the process
                proc_close($process);
                $endTime = microtime(true);
                $executionTime = $endTime - $startTime;
                $endMemory = memory_get_usage();
                $memoryUsed = $endMemory - $startMemory;
                echo $output;
                if($executionTime >= $timeLimitInSeconds) {
                    $output="Time Limit Excedeed";
                    break;
                }else if ($memoryUsed > $memoryLimitInBytes) {
                    echo "Memory Limit Exceeded!";
                } else {
                    $ans_file="ans.txt";
                    file_put_contents($ans_file,$output);
                    $process = proc_open('./compiled_code.exe', [
                        0 => ['pipe', 'r'],
                        1 => ['pipe', 'w'],
                        2 => ['pipe', 'w'],
                    ], $pipes);
                    if (is_resource($process)) {
                        fwrite($pipes[0], $row['case_input']);
                        fclose($pipes[0]);
                        $output = stream_get_contents($pipes[1]);
                        $errors = stream_get_contents($pipes[2]);
                        echo $output;
                        if($output!="ACCEPTED"){
                            break;
                        }
                    }
                }
            }
        }
        $sql = "insert into submission (email,problem_id,verdict,shomoy) values('$email', '$problem_ID', '$output','$time')";
        $result = $connected->query($sql);

    } else {
        $sql = "insert into submission (email,problem_id,verdict,shomoy) values('$email', '$problem_ID', 'Compilation Error','$time')";
        $result = $connected->query($sql);
    }

    // Close the database connection
    $connected->close();

    // Redirect to the status page
    // header("Location: http://localhost/PHP%20Code/Status.php");
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
            <option value="1">C</option>
            <option value="2">C++</option>
        </select></br></br>

        <div class="CodeContainer">
            <label for="Source_Code">&nbsp;Source Code:&nbsp;</label>
            <textarea id="Source_Code" name="Source_Code" placeholder="// Write your code here" class="Source_Code"></textarea>
        </div>

        <div class="SubmitButtonContainer">
            <button class="SubmitButton" type="submit">Submit</button>
        </div>
    </form>
</div>

<footer class="footer">
    <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
</footer>
</body>
</html>
