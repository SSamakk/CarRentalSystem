<html>
<link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
<body>
<?php
    $database_host = "localhost";
    $database_user = "root";
    $database_pass = "";
    $database_name = "carrental";

    if (isset($_GET['start']) && isset($_GET['end'])) {
        $start = $_GET['start'];
        $end = $_GET['end'];
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'reservation_id';

    $connection = mysqli_connect($database_host, $database_user, $database_pass, $database_name);
    if (mysqli_connect_errno()) {
        die("Failed connecting to MySQL database. Invalid credentials" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
    }

    // Use prepared statement to avoid SQL injection
    $reservationQuery = "SELECT r.*, u.*, c.* 
                        FROM carrental.reservation r
                        INNER JOIN carrental.user u ON r.user_id = u.user_id
                        INNER JOIN carrental.car c ON r.car_id = c.car_id
                        WHERE r.reserve_date BETWEEN ? AND ? AND r.$category LIKE ?";

    $stmt = mysqli_prepare($connection, $reservationQuery);

    if ($stmt) {
        if (empty($search)) {
            // If search term is empty, fetch all records
            $reservationQuery = "SELECT r.*, u.*, c.* 
                                FROM carrental.reservation r
                                INNER JOIN carrental.user u ON r.user_id = u.user_id
                                INNER JOIN carrental.car c ON r.car_id = c.car_id
                                WHERE r.reserve_date BETWEEN ? AND ?";
            $stmt = mysqli_prepare($connection, $reservationQuery);
            mysqli_stmt_bind_param($stmt, "ss", $start, $end);
        } else {
            // If search term is not empty, apply the search term and category
            $searchTerm = "%$search%";
            mysqli_stmt_bind_param($stmt, "sss", $start, $end, $searchTerm);
        }

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        echo "<h1><center>Reservations</h1><br><br>";
        ?>
        <center>
            <form action="viewreservations.php" method="GET">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                Search:
                <select name="category">
                    <option value="reservation_id" <?php echo ($category === 'reservation_id') ? 'selected' : ''; ?>>Reservation ID</option>
                    <option value="car_id" <?php echo ($category === 'car_id') ? 'selected' : ''; ?>>Car ID</option>
                    <option value="user_id" <?php echo ($category === 'user_id') ? 'selected' : ''; ?>>User ID</option>
                    <option value="reserve_date" <?php echo ($category === 'reserve_date') ? 'selected' : ''; ?>>Reserve Date</option>
                    <option value="pickup_date" <?php echo ($category === 'pickup_date') ? 'selected' : ''; ?>>Pickup Date</option>
                    <option value="return_date" <?php echo ($category === 'return_date') ? 'selected' : ''; ?>>Return Date</option>
                </select>
                <input type="text" name="search" value="<?php echo $search; ?>">
                <input type="submit" value="Search">
            </form>
            <table border='1'>
                <tr>
                    <th>Reservation ID</th>
                    <th>Car ID</th>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>User Birthdate</th>
                    <th>User Address</th>
                    <th>User Email</th>
                    <th>Car Office ID</th>
                    <th>Car Model</th>
                    <th>Car Year</th>
                    <th>Car Price</th>
                    <th>Car Status</th>
                    <th>Reserve Date</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["reservation_id"] . "</td>";
                        echo "<td>" . $row["car_id"] . "</td>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>" . $row["fname"] . " " . $row["lname"] . "</td>";
                        echo "<td>" . $row["bdate"] . "</td>";
                        echo "<td>" . $row["caddress"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["office_id"] . "</td>";
                        echo "<td>" . $row["model"] . "</td>";
                        echo "<td>" . $row["year"] . "</td>";
                        echo "<td>" . $row["price"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>" . $row["reserve_date"] . "</td>";
                        echo "<td>" . $row["pickup_date"] . "</td>";
                        echo "<td>" . $row["return_date"] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>

            <!-- Add a back button -->
            <a href="/CarRental/Admin/Frontend/reservationreport.html">Back to Main Page</a>
        </center>
        <?php
        mysqli_stmt_close($stmt);
    } else {
        echo "Error in prepared statement: " . mysqli_error($connection);
    }

    mysqli_close($connection);
?>
</body>
</html>
