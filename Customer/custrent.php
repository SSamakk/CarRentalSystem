<html>
    <head>
        <title>Car Rental </title>
        <link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
    </head>

    <?php
            $email = htmlspecialchars($_GET["email"]);
            $car_id = htmlspecialchars($_GET["car_id"]);
            // echo"$email";
            // echo"$car_id";
    ?>

    <body>
        <?php
            $database_host = "localhost";
            $database_user = "root";
            $database_pass = "";
            $database_name = "carrental";
            $connection = mysqli_connect($database_host, $database_user, $database_pass, $database_name);
            $total = 0;
            if (mysqli_connect_errno()) {
                die("Failed connecting to MySQL database. Invalid credentials" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
            }

            function calculate($connection, $pickup_date, $return_date, $car_id) {                
                $sqlPrice = "SELECT price * DATEDIFF('$return_date', '$pickup_date') AS 'total_price' FROM carrental.car WHERE car_id='$car_id'";

                $result = mysqli_query($connection, $sqlPrice);
                return $result;
            }

            $pickup_date = isset($_GET['pickup_date']) ? $_GET['pickup_date'] : '';
            $return_date = isset($_GET['return_date']) ? $_GET['return_date'] : '';

            if ($_SERVER["REQUEST_METHOD"] == "GET" && $pickup_date !== '' && $return_date !== '') {
                $result = calculate($connection, $pickup_date, $return_date, $car_id);
                $row1 = mysqli_fetch_assoc($result);
                $total = $row1['total_price'];
                if ($total > 0 ){

                    $sqlUser = "SELECT user_id FROM carrental.user WHERE email='$email'";
                    $result_id = mysqli_query($connection, $sqlUser);
                    $id = mysqli_fetch_assoc($result_id);
                    $user_id = $id['user_id'];

                    $sql = "INSERT INTO reservation(car_id, user_id ,pickup_date, return_date) VALUES ('$car_id','$user_id ','$pickup_date','$return_date')";
                    $connection->query($sql);

                    $sqlID = "SELECT reservation_id FROM carrental.reservation WHERE user_id='$user_id'";
                    $result_reservation_id = mysqli_query($connection, $sqlID);
                    $result_reservation = mysqli_fetch_assoc($result_reservation_id);
                    $reservation_id = $result_reservation['reservation_id'];

                    $sql = "INSERT INTO payment(reservation_id ,amount, payment_date) VALUES ('$reservation_id','$total','$pickup_date')";
                    $connection->query($sql);

                    $updateSql = "UPDATE car SET status = 'rented' WHERE car_id = '$car_id'";
                    mysqli_query($connection, $updateSql);
                    
                    echo '<script>window.location.href = "/CarRental/Customer/rented.php?total=' . $total . '&email=' . $email . '";</script>';
                }
            }

            $sqlUser = "SELECT fname FROM carrental.user WHERE email='$email'";
            $result_user = mysqli_query($connection, $sqlUser);
            // Fetch the data from the result set
            $row_user = mysqli_fetch_assoc($result_user);
            $fname = $row_user['fname'];
        ?>

        <div class="carcotainer">
            <form method="GET" onsubmit = "return formatDateInput()">
                <input type="hidden" name="email" value="<?php echo $email;?>">
                <input type="hidden" name="car_id" value="<?php echo $car_id;?>">
                <h1> RENT CAR </h1>
                    Pickup Date: <input type="date" name="pickup_date" id="pickup_date" value="<?php echo $pickup_date; ?>"/><br><br>
                    Return Date: <input type="date" name="return_date" id="return_date" value="<?php echo $return_date;?>"/><br><br>
                    Customer Name:<br><type="text" name="fname" id="fname"/><?php echo ($fname);?><br><br>
                    Email:<br><type="text" name="email" id="email"/><?php echo ($email);?><br><br>
                    Car ID:<br><type="text" name="car_id" id="car_id"/><?php echo ($car_id);?><br><br>
                <input type="submit" value="Submit" class="submitbutton"/>
            </form> 
            <br> <br></br></br>
        </div>	

        <?php
            echo "<div class='button'><a href='/CarRental/Customer/custcars.php?email=$email'>Back to Main Page</a></div>";
        ?>

        <script>
            function formatDateInput() {
                var startDateInput = document.getElementById("pickup_date");
                var endDateInput = document.getElementById("return_date");
                var error_message='';    
                
                // Check if the dates are not empty
                if (startDateInput.value === "") {
                    error_message +="Please enter start date \n";
                }
                if (endDateInput.value === "") {
                    error_message +="Please enter end date \n";
                }
                if(error_message !== ""){
                    alert(error_message);
                    return false;
                }

                // Format the start date
                var startDate = new Date(startDateInput.value);
                var formattedStartDate = startDate.getFullYear() + '-' + ('0' + (startDate.getMonth() + 1)).slice(-2) + '-' + ('0' + startDate.getDate()).slice(-2);
                startDateInput.value = formattedStartDate;

                // Format the end date
                var endDate = new Date(endDateInput.value);
                var formattedEndDate = endDate.getFullYear() + '-' + ('0' + (endDate.getMonth() + 1)).slice(-2) + '-' + ('0' + endDate.getDate()).slice(-2);
                endDateInput.value = formattedEndDate;

                return true;
            }
        </script>
	</body>
</html>