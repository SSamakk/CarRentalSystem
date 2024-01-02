<html>
    <head>
        <title>Car Rental </title>
        <link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
    </head>

    <?php
        $email = htmlspecialchars($_GET["email"]);
        $total = $_GET["total"];
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {   
            echo '<script>window.location.href = "/CarRental/Customer/custcars.php?email=' . $email . '";</script>';
        }
    ?>

    <body>
        <div class="carcotainer">
            <form method="POST">
                <h1> Successful Rented </h1>
                <br><br>  
                <h2>Total Price: <type="text" name="total" id="total" ><?php echo $total; ?> </h2><br><br>  
                <input type="submit" value="Return to main page" class="submitbutton"/>
            </form> 
            <br> <br></br></br>
        </div>	
	</body>
</html>