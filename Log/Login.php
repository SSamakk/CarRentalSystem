<html>
<head>
    <title>login</title>
    <link href="../Theme/customTheme.css" rel="stylesheet" type="text/css" >
</head>
<body>
    <?php

        session_start();
        $connection = mysqli_connect("localhost", "root" ,"", "carrental" );

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {   
            $email = $_POST['email'];
            $password = $_POST['password'];
            
                ///////////EMAIL VALIDATION/////////////
                $validEmailFormat = "example@example.com";
                $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-z]+\.[a-z]{2,3}$/";
                if (!preg_match($emailPattern, $email)) {
                    echo '<script>alert("Invalid email format. Please enter an email as the following format :' . $validEmailFormat . '");</script>';
                } 
                ///////////////////////////////////////
                else {
                $sql1 = "SELECT * FROM user WHERE email = '$email' ";
                $sql2 = "SELECT * FROM `system` WHERE email = '$email' ";

                $result1 = $connection->query($sql1);
                $result2 = $connection->query($sql2);

                if ($row = $result1->fetch_assoc()){ // Customer email exists
                    
                    $dbPassword = $row['password'];
                    if(($password == $dbPassword)){ //Password exists in user
                        echo '<script>window.location.href = "/CarRental/Customer/custcars.php?email=' . $email . '";</script>'; // Customer
                        exit;
                    }
                    else
                    {
                        echo '<script>alert("Invalid email or password. Please try again.");</script>'; // wrong password
                    }
                    
                }
                elseif($row = $result2->fetch_assoc()){ // Admin email exists
                    
                    $dbPassword = $row['password'];
                    //Password exists in system
                    if(($password == $dbPassword)){ 
                        echo '<script>window.location.href = "/CarRental/Admin/Frontend/customer.html";</script>'; // ADMIN
                        exit;
                    }
                    else
                    {
                        echo '<script>alert("Invalid email or password. Please try again.");</script>'; // wrong password
                    }
                    
                }

                else{
                    echo '<script>alert("Invalid email or password. Please try again.");</script>'; // email doesnt exist
                }
        }
            
        }
    ?>

    <div class="container">
        <h1>Login</h1>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return validateForm()" >
            <input type="text" placeholder="E-mail" name="email" id="email">
            <br>
            <input type="password" placeholder="Password" name="password" id="password">
            <br>
            <button type="submit">Login</button>
        </form>

        <p>Create a new account? <a href="register.php">Register</a></p>
    </div>

    <script>
        function validateForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            var error_message='';

            if (email === "") {
                error_message +="Email Field is Empty \n";
            } 

            if (password === "") {
                error_message +="Password Field is Empty \n";
            }

            if(error_message !== ""){
                alert(error_message);
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
