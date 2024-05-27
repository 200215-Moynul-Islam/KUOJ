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

            .Content .Send{
                display: flex;
                justify-content: center;
            }

            .Content .Send .SendButton{
                width: 60px;
                height: 30px;
                margin-top: 5px;
                background-color: #224957;
                color: #ffffff;
                border-radius: 10px;
                font-size: 15px;
                font-weight: bold;
            }
            .Content .Send .SendButton:hover{
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
            //Importing PHPMailer classes into the global namespace
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            use PHPMailer\PHPMailer\Exception;

            //Including PHPMailer classes
            require 'PHPMailer/src/PHPMailer.php';
            require 'PHPMailer/src/SMTP.php';
            require 'PHPMailer/src/Exception.php';


            session_start();//Starting a PHP session
            $flag=FALSE;

            //Checking if the "Remember Me" cookie exists
            if(isset($_COOKIE["remember_me"])){
                //Automatically loging in using the email from the cookie
                $email=$_COOKIE["remember_me"];//Accessing the email from the cookie
                $_SESSION["Email"]=$email;//Storing the email in the session
                header("Location: http://localhost/PHP%20Code/Home.php");//Redirecting to Home page
                exit;
            }

            //function for sending new password to the user
            function sendEmail($to,$subject,$body){
                //Creating a new PHPMailer instance
                $mail=new PHPMailer(true);
            
                try{
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host='smtp.gmail.com';
                    $mail->SMTPAuth=true;
                    $mail->Username='kuoj0000@gmail.com';
                    $mail->Password='mgig prsj vmyz rwqk';
                    $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port=587;
            
                    //Sender and reciever settings
                    $mail->setFrom('kuoj0000@gmail.com','KUOJ');
                    $mail->addAddress($to);
            
                    //Content settings
                    $mail->isHTML(true);
                    $mail->Subject=$subject;
                    $mail->Body=$body;
            
                    $mail->send();
                    return true;
                }catch (Exception $e){
                    return false;
                }
            }

            function generateRandomPassword(){
                $length=12;
                //Defining the characters that can be used in the password
                $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_';
            
                //Getting the total number of characters
                $charCount=strlen($characters);
            
                //Initializing the password variable
                $password='';
            
                //Generating random characters to build the password
                for($i=0;$i<$length;$i++){
                    $randomIndex=rand(0,$charCount-1);
                    $password.=$characters[$randomIndex];
                }
            
                return $password;
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

                //SQL query to validate the account
                $sql="SELECT * FROM user_info WHERE email='$email'";
                $result=$connected->query($sql);

                //Checking whether the account is valid or not
                if($result->num_rows==1){
                    $newPass=generateRandomPassword();
                    $hashPass=password_hash($newPass,PASSWORD_DEFAULT);
                    $subject="Password Recovery";
                    $message="Your new Password is: ".$newPass;
                    $tem=sendEmail($email,$subject,$message);//sending mail
                    if($tem){
                        //Updating the password in the database
                        $sql="UPDATE user_info SET user_password='$hashPass' WHERE email='$email'";
                        $connected->query($sql);
                        //Redirecting to ForgotPasswordConfirmation page
                        header("Location: http://localhost/PHP%20Code/ForgotPasswordConfirmation.php");
                        exit;
                    }else{
                        echo "Unable to send the mail.";
                    }
                }else{
                    //Invalid account
                    $flag=TRUE;
                }

                //Closing the database connection
                $connected->close();
            }
        ?>


        <div class="Content">
            <form method="post">
                <input id="Email" name="Email" type="email" placeholder="E-mail" required></br>
                <div class="Send">
                    <button class="SendButton">Send</button>
                </div>
                <div>
                    <p style="color:red; margin-left:90px;"><?php if($flag){echo "Invalid User Email";}else{echo "&nbsp";}?><p>
                </div>
            </form>
        </div>

        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>