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
    $category = isset($_GET['category']) ? $_GET['category'] : 'amount'; // Default category is 'amount'

    $connection = mysqli_connect($database_host, $database_user, $database_pass, $database_name);
    if (mysqli_connect_errno()) {
        die("Failed connecting to MySQL database. Invalid credentials" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
    }

    // Use prepared statement to avoid SQL injection
    if ($category === 'amount') {
        $res = "SELECT * FROM carrental.payment WHERE payment_date BETWEEN ? AND ? AND $category = ?";
    } else {
        $res = "SELECT * FROM carrental.payment WHERE payment_date BETWEEN ? AND ? AND $category LIKE ?";
    }

    $stmt = mysqli_prepare($connection, $res);

    if ($stmt) {
        if (empty($search)) {
			// If search term is empty, fetch all records
			$res = "SELECT * FROM carrental.payment WHERE payment_date BETWEEN ? AND ?";
			$stmt = mysqli_prepare($connection, $res);
			mysqli_stmt_bind_param($stmt, "ss", $start, $end);
		} else {
			// If search term is not empty, apply the search term and category
			if ($category === 'amount') {
				mysqli_stmt_bind_param($stmt, "ssd", $start, $end, $search);
			} else {
				$searchTerm = "%$search%";
				mysqli_stmt_bind_param($stmt, "sss", $start, $end, $searchTerm);
			}
		}
		

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        echo "<h1><center>Payments Per Day</h1><br><br>";
        ?>
        <center>
            <form action="viewpayments.php" method="GET">
                <input type="hidden" name="start" value="<?php echo $start; ?>">
                <input type="hidden" name="end" value="<?php echo $end; ?>">
                Search:
                <select name="category">
                    <option value="payment_id" <?php echo ($category === 'payment_id') ? 'selected' : ''; ?>>Payment ID</option>
                    <option value="reservation_id" <?php echo ($category === 'reservation_id') ? 'selected' : ''; ?>>Reservation</option>
                    <option value="amount" <?php echo ($category === 'amount') ? 'selected' : ''; ?>>Amount</option>
                    <option value="payment_date" <?php echo ($category === 'payment_date') ? 'selected' : ''; ?>>Payment Date</option>
                </select>
                <input type="text" name="search" value="<?php echo $search; ?>">
                <input type="submit" value="Search">
            </form>
            <table border='1'>
                <tr><br>
                    <th> &nbsp; &nbsp; Payment ID &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Reservation ID &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Amount &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Payment Date &nbsp; &nbsp; </th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["payment_id"] . "</td>";
                        echo "<td>" . $row["reservation_id"] . "</td>";
                        echo "<td>" . $row["amount"] . "</td>";
                        echo "<td>" . $row["payment_date"] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>

            <!-- Add a back button -->
            <a href="/CarRental/Admin/Frontend/paymentreport.html">Back to Main Page</a>
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
