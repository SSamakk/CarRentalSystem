<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="/CarRental/Theme/customTheme.css">
</head>

<body>
    <?php
          session_start();
          $connection = mysqli_connect("localhost", "root" ,"", "carrental" );

          if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $bdate = $_POST['Birthdate'];
                $caddress = $_POST['Address'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM user WHERE email = '$email' ";
                $result = $connection->query($sql);

                if ($result->fetch_assoc()) // Email already exists
                {
                    echo '<script>alert("Email Already Exists! Please try another email.");</script>';
                } 
                else {
                    $sql = "INSERT INTO user(fname, lname, bdate, caddress, email, password) VALUES ('$fname', '$lname', '$bdate', '$caddress', '$email', '$password')";
                    $connection->query($sql);

                    header("Location: login.php");
                    exit;
                }
            
            }
           $connection->close();
    ?>

    <div class="container">
        <h1>Register</h1>
        
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>"  onsubmit="return validateForm()">
            <input type="text" placeholder="FName" name="fname" id="fname">
            <br>
            <input type="text" placeholder="LName" name="lname" id="lname">
            <br>
            BirthDate : <input type="date" placeholder="Birthdate" name="Birthdate" id="Birthdate">
            <br>
            <input type="text" placeholder="Address" name="Address" id="Address">
            <br>
            <input type="email" placeholder="E-mail" name="email" id="email">
            <br>
            <input type="password" placeholder="Password" name="password" id="password">
            <br>
            <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password">
            <br>
            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <script>
        function validateForm() {
            var fname = document.getElementById("fname").value;
            var lname = document.getElementById("lname").value;
            var bdate = document.getElementById("Birthdate").value;
            var caddress = document.getElementById("Address").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            var error_message='';
            
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-z]+\.[a-z]{2,3}$/;
            if (!emailPattern.test(email) && email !== "") {
                error_message += "Invalid Email Format. Please enter an email as the following format: example@example.com \n";
            }

            if (fname === "") {
                error_message +="First Name Field is Empty \n";
            }

            if (lname === "") {
                error_message +="Last Name Field is Empty \n";
            }

            if (bdate === "") {
                error_message +="Birthdate Field is Empty \n";
            }

            if (caddress === "") {
                error_message +="Address Field is Empty \n";
            }

            if (email === "") {
                error_message +="Email Field is Empty \n";
            }

            if (password === "") {
                error_message +="Password Field is Empty \n";
            }

            if (confirm_password === "") {
                error_message +="Confirm Password Field is Empty \n";
            }

            if (password !== confirm_password) {
                error_message += "Confirm Password isn't correct \n";
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