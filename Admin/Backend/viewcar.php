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

    function performSearch($connection, $search, $category) {
        if (empty($search)) {
            // Display all data if no search term provided
            $searchSql = "SELECT * FROM carrental.car";
        } else {
            if($category === 'status' ||$category === 'model' )
                $searchSql = "SELECT * FROM carrental.car WHERE $category LIKE '$search%'";
            else{
                $searchSql = "SELECT * FROM carrental.car WHERE $category = '$search'";
            }
        }

        $result = mysqli_query($connection, $searchSql);
        return $result;
    }

    // Search logic
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'car_id';

    // Update status if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['car_id']) && isset($_POST['status'])) {
        $car_id = $_POST['car_id'];
        $status = $_POST['status'];

        $updateSql = "UPDATE car SET status = '$status' WHERE car_id = '$car_id'";
        mysqli_query($connection, $updateSql);
    }
    // Update price if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['car_id']) && isset($_POST['price'])) {
        $car_id = $_POST['car_id'];
        $price = $_POST['price'];

        $updateSql = "UPDATE car SET price = '$price' WHERE car_id = '$car_id'";
        mysqli_query($connection, $updateSql);
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
        $result = performSearch($connection, $search, $category);
    } else {
        // Display all data when the page is loaded without searching
        $result = performSearch($connection, '', 'car_id');
    }

    echo "<h1><center>Cars</h1><br><br>";
    ?>
    <center>
        <!-- Search form with category dropdown -->
        <form method="GET">
            <label for="search">Search: </label>
            <input type="text" name="search" id="search" value="<?php echo $search; ?>">
            &nbsp; &nbsp; 
            <input type="submit" value="Search">
            <br>
            <label for="category">Category: </label>
            <select name="category" id="category">
                <option value="car_id" <?php echo ($category == 'car_id') ? 'selected' : ''; ?>>Car ID</option>
                <option value="office_id" <?php echo ($category == 'office_id') ? 'selected' : ''; ?>>Office ID</option>
                <option value="model" <?php echo ($category == 'model') ? 'selected' : ''; ?>>Model</option>
                <option value="year" <?php echo ($category == 'year') ? 'selected' : ''; ?>>Year</option>
                <option value="status" <?php echo ($category == 'status') ? 'selected' : ''; ?>>Status</option>
                <option value="price" <?php echo ($category == 'price') ? 'selected' : ''; ?>>Price</option>
            </select>
        </form>

        <table border='1'>
            <tr><br>
                <th> &nbsp; &nbsp; Car ID &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Office ID &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Model &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Year &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Price/Day &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Status &nbsp; &nbsp; </th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["car_id"] . "</td>";
                    echo "<td>" . $row["office_id"] . "</td>";
                    echo "<td>" . $row["model"] . "</td>";
                    echo "<td>" . $row["year"] . "</td>";
                    echo "<td>";
                    // Display input field for price
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='car_id' value='" . $row["car_id"] . "'/>";
                    echo "<input type='text' name='price' value='" . $row["price"] . "'/>";
                    echo "<input type='submit' value='Update Price'/>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    // Display dropdown for status
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='car_id' value='" . $row["car_id"] . "'/>";
                    echo "<select name='status'>";
                    echo "<option value='active' " . ($row["status"] == 'active' ? 'selected' : '') . ">Active</option>";
                    echo "<option value='out of service' " . ($row["status"] == 'out of service' ? 'selected' : '') . ">Out of Service</option>";
                    echo "<option value='rented' " . ($row["status"] == 'rented' ? 'selected' : '') . ">Rented</option>";
                    echo "</select>";
                    echo "<input type='submit' value='Update Status'/>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>

        <!-- Add a back button -->
        <a href="/CarRental/Admin/Frontend/cars.html">Back to Main Page</a>
    </center>
</body>

</html>
