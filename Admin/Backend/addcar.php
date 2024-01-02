<html>
    <link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
    <body>
        <?php
            $database_host = "localhost";
            $database_user = "root";
            $database_pass = "";
            $database_name = "carrental";

            $connection = mysqli_connect($database_host, $database_user, $database_pass, $database_name);
            if (mysqli_connect_errno()) {
                die("Failed connecting to MySQL database. Invalid credentials" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
            }

            $car_id = $_POST["car_id"];
            $office_id = $_POST["office_id"];
            $model = $_POST["model"];
            $year = $_POST["year"];
            $price = $_POST["price"];
            $status = $_POST["status"];

            // Check if the car_id already exists
            $checkCarIdSql = "SELECT * FROM car WHERE car_id = '$car_id'";
            $checkCarIdResult = $connection->query($checkCarIdSql);

            // Check if the office_id does not exist
            $checkOfficeIdSql = "SELECT * FROM office WHERE office_id = '$office_id'";
            $checkOfficeIdResult = $connection->query($checkOfficeIdSql);

            if ($checkCarIdResult->fetch_assoc() && !$checkOfficeIdResult->fetch_assoc()){
                // Car ID already exists & Office ID does not exist
                echo '<script>alert("Car ID already exists! & Office ID does not exist!.");</script>';
            }
            elseif ($checkCarIdResult->fetch_assoc()) {
                // Car ID already exists
                echo '<script>alert("Car ID already exists! Please try a different Car ID.");</script>';
            } elseif (!$checkOfficeIdResult->fetch_assoc()) {
                // Office ID does not exist
                echo '<script>alert("Office ID does not exist! Please enter a valid Office ID.");</script>';
            } else {
                // If car_id exists and office_id doesn't exist, insert the new record
                $sql = "INSERT INTO car(car_id, office_id, model, year, price, status) VALUES ('$car_id','$office_id','$model','$year','$price','$status')";
                $connection->query($sql);

                echo '<script>alert("New car has been successfully added");</script>';
            }
            echo "<script> window.location.href='/CarRental/Admin/Frontend/cars.html'; </script>";
        ?>
    </body>
</html>
