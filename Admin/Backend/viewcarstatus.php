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

    function getCarsStatusOnDate($connection, $date) {
        $statusSql = "SELECT car.car_id, car.office_id, car.model, car.year, car.price,
                            CASE WHEN ('$date' BETWEEN reservation.pickup_date AND reservation.return_date) THEN 'Rented' ELSE 'Active' END AS status,
                            reservation.pickup_date, reservation.return_date
                      FROM carrental.car
                      LEFT JOIN reservation ON car.car_id = reservation.car_id";

        $result = mysqli_query($connection, $statusSql);
        return $result;
    }

    // Date logic
    $specificDate = isset($_GET['specificDate']) ? $_GET['specificDate'] : date('Y-m-d');

    // Get cars status on the specific date
    $result = getCarsStatusOnDate($connection, $specificDate);

    echo "<h1><center>Cars Status on $specificDate</h1><br><br>";
    ?>
    <center>
        <table border='1'>
            <tr><br>
                <th> &nbsp; &nbsp; Car ID &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Office ID &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Model &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Year &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Price/Day &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Status &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Pickup Date &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Return Date &nbsp; &nbsp; </th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["car_id"] . "</td>";
                    echo "<td>" . $row["office_id"] . "</td>";
                    echo "<td>" . $row["model"] . "</td>";
                    echo "<td>" . $row["year"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["pickup_date"] . "</td>";
                    echo "<td>" . $row["return_date"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No cars found on $specificDate</td></tr>";
            }
            ?>
        </table>

        <!-- Date filter form -->
        <form method="GET">
            <label for="specificDate">View status on:</label>
            <input type="date" name="specificDate" value="<?php echo $specificDate; ?>">
            <input type="submit" value="Filter">
        </form>

        <!-- Add a back button -->
        <a href="/CarRental/Admin/Frontend/carstatus.html">Back to Main Page</a>
    </center>
</body>

</html>
