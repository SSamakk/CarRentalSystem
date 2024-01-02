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
            $searchSql = "SELECT * FROM carrental.office";
        } else {
            if($category === 'office_id' || $category === 'phone' ) // Numerical
                $searchSql = "SELECT * FROM carrental.office WHERE $category = '$search'";
            
            else{
                $searchSql = "SELECT * FROM carrental.office WHERE $category LIKE '$search%'";
            }
        }

        $result = mysqli_query($connection, $searchSql);
        return $result;
    }

    // Search logic
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'office_id';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
        $result = performSearch($connection, $search, $category);
    } else {
        // Display all data when the page is loaded without searching
        $result = performSearch($connection, '', 'office_id');
    }

    echo "<h1><center>Offices</h1><br><br>";
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
                <option value="office_id" <?php echo ($category == 'office_id') ? 'selected' : ''; ?>>Office ID</option>
                <option value="phone" <?php echo ($category == 'phone') ? 'selected' : ''; ?>>Phone</option>
                <option value="email" <?php echo ($category == 'email') ? 'selected' : ''; ?>>E-mail</option>
                <option value="country" <?php echo ($category == 'country') ? 'selected' : ''; ?>>Country</option>
                <option value="city" <?php echo ($category == 'city') ? 'selected' : ''; ?>>City</option>
            </select>
        </form>

        <table border='1'>
            <tr><br>
                <th> &nbsp; &nbsp; Office ID &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Phone &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; E-mail &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Country &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; City &nbsp; &nbsp; </th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["office_id"] . "</td>";
                    echo "<td>" . $row["phone"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["country"] . "</td>";
                    echo "<td>" . $row["city"] . "</td>";
                }
            }
            ?>
        </table>

        <!-- Add a back button -->
        <a href="/CarRental/Admin/Frontend/office.html">Back to Main Page</a>
    </center>
</body>

</html>
