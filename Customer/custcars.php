<html>
    <link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
    <body>
        <?php
            $email = urldecode(htmlspecialchars($_GET["email"]));
            // echo"$email"
        ?>

        <!-- logout button -->
        <div class="logoutButton">
            <a href="/CarRental/Log/Login.php">Logout</a>
        </div>

        <head>
            <title>Car Rental </title>
            <link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
        </head>
    
    
		<div class="logoBackground">
  	        <div class="logo"><h6>RENTER<h6></div>
        </div>
		<div class="menu">
            <ul>
                <li id="active"><a href="">Cars</a></li>
            </ul>
        </div>

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
                    $searchSql = "SELECT * FROM carrental.car Natural JOIN carrental.office WHERE status = 'Active' ";
                } else {
                    if($category === 'model' || $category === 'country' || $category === 'city')
                        $searchSql = "SELECT * FROM carrental.car Natural JOIN carrental.office WHERE $category LIKE '$search%' and status = 'Active'";
                    else{
                        $searchSql = "SELECT * FROM carrental.car Natural JOIN carrental.office WHERE $category = '$search' and status = 'Active'";
                    }
                }

                $result = mysqli_query($connection, $searchSql);
                return $result;
            }

            // Search logic
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : 'car_id';

            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
                $result = performSearch($connection, $search, $category);
            } else {
                // Display all data when the page is loaded without searching
                $result = performSearch($connection, '', 'car_id');
            }
        ?>

        <center>
            <!-- Search form with category dropdown -->
            <form method="GET">
            <input type="hidden" name="email" value="<?php echo $email;?>">
                <label for="search">Search: </label>
                <input type="text" name="search" id="search" value="<?php echo $search; ?>">
                &nbsp; &nbsp; 
                <input type="submit" value="Search">
                <br>
                <label for="category">Category: </label>
                <select name="category" id="category">
                    <option value="car_id" <?php echo ($category == 'car_id') ? 'selected' : ''; ?>>Car ID</option>
                    <option value="country" <?php echo ($category == 'country') ? 'selected' : ''; ?>>Office Country</option>
                    <option value="city" <?php echo ($category == 'city') ? 'selected' : ''; ?>>Office City</option>
                    <option value="model" <?php echo ($category == 'model') ? 'selected' : ''; ?>>Model</option>
                    <option value="year" <?php echo ($category == 'year') ? 'selected' : ''; ?>>Year</option>
                    <option value="price" <?php echo ($category == 'price') ? 'selected' : ''; ?>>Price</option>
                </select>
            </form>

            <table border='1'>
                <tr><br>
                    <th> &nbsp; &nbsp; Car ID &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Office Country &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Office City &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Model &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Year &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Price/Day &nbsp; &nbsp; </th>
                    <th> &nbsp; &nbsp; Rent &nbsp; &nbsp; </th>
                </tr>
                <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row["car_id"] . "</td>";
                            echo "<td>" . $row["country"] . "</td>";
                            echo "<td>" . $row["city"] . "</td>";
                            echo "<td>" . $row["model"] . "</td>";
                            echo "<td>" . $row["year"] . "</td>";
                            echo "<td>" . $row["price"] . "</td>";
                            echo "<td>";
                            // Display input field for rent
                            echo "<form method='POST'>";
                            echo "<input type='hidden' name='car_id' value='" . $row["car_id"] . "'/>";
                            echo "<hr style='height:5pt; visibility:hidden;'/>
                                  <div class='button'><a href='/CarRental/Customer/custrent.php?email=$email&car_id={$row['car_id']}'>Rent Here</a></div>
                                  <hr style='height:5pt; visibility:hidden;'/>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </table>
        </center>

	</body>
</html>