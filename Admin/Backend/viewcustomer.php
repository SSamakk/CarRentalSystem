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
            $searchSql = "SELECT * FROM carrental.user";
        } else {
            if($category === 'user_id' ) // Numerical
                $searchSql = "SELECT * FROM carrental.user WHERE $category = '$search'";
            
            else{
                $searchSql = "SELECT * FROM carrental.user WHERE $category LIKE '$search%'";
            }
        }

        $result = mysqli_query($connection, $searchSql);
        return $result;
    }

    // Search logic
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'fname';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
        $result = performSearch($connection, $search, $category);
    } else {
        // Display all data when the page is loaded without searching
        $result = performSearch($connection, '', 'fname');
    }

    echo "<h1><center>Customers</h1><br><br>";
    ?>
    <center>

        <!-- Search form with category dropdown -->
        <form method="GET" onsubmit="return performSearch()">
            <label for="search">Search: </label>
            <input type="text" name="search" id="search" value="<?php echo $search; ?>">
            &nbsp; &nbsp; 
            <input type="submit" value="Search">
            <br> 
            <label for="category">Category: </label>
            <select name="category" id="category">
                <option value="user_id" <?php echo ($category == 'user_id') ? 'selected' : ''; ?>>Customer ID</option>
                <option value="fname" <?php echo ($category == 'fname') ? 'selected' : ''; ?>>First Name</option>
                <option value="lname" <?php echo ($category == 'lname') ? 'selected' : ''; ?>>Last Name</option>
                <option value="bdate" <?php echo ($category == 'bdate') ? 'selected' : ''; ?>>Birthdate</option>
                <option value="caddress" <?php echo ($category == 'caddress') ? 'selected' : ''; ?>>Address</option>
                <option value="email" <?php echo ($category == 'email') ? 'selected' : ''; ?>>Email</option>
            </select>
            &nbsp; &nbsp; 
        </form>

        <table border='1'>
            <tr><br>
                <th> &nbsp; &nbsp; Customer ID &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; FName &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Lname &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Birthdate &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Address &nbsp; &nbsp; </th>
                <th> &nbsp; &nbsp; Email &nbsp; &nbsp; </th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["user_id"] . "</td>";
                    echo "<td>" . $row["fname"] . "</td>";
                    echo "<td>" . $row["lname"] . "</td>";
                    echo "<td>" . $row["bdate"] . "</td>";
                    echo "<td>" . $row["caddress"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>

        <!-- Add a back button -->
        <a href="/CarRental/Admin/Frontend/customer.html">Back to Main Page</a>
    </center>
</body>

</html>
