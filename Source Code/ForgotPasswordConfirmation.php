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



            .content{
                flex: 1;/*Allow the content to take available space*/
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .content .Message{
                width: 285px;
                height: 75px;
                background-color: #E5E5E5;
                border-radius: 10px;
            }
            .content .Message p{
                margin: 11px;
                font-size: 15px;
            }
            .content .Message nav a{
                color: #FFFFFF;
                text-decoration: none;
                padding: 5px 10px 5px 10px;
                background-color: #14A8E8;
                border-radius: 5px;
                margin-left: 120px;
            }
            .content .Message nav a.OK:hover{
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
        <div class="content">
            <div class="Message">
                <p>Your password is sent to your e-mail.</p>
                <nav>
                    <a href="http://localhost/PHP%20Code/Login.php" class="OK">OK</a>
                </nav>
            </div>
        </div>
        


        <footer class="footer">
            <p>&copy; 2023 KU_ErrorMakers. All rights reserved.</p>
        </footer>
    </body>
</html>