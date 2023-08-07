<!-- header.php -->
<header class="header">
    <link rel="stylesheet" type="text/css" href="./styles.css">
    <link rel="stylesheet" type="text/css" href="./nav.css">
    <script src="script.js"></script>

    <!--logo and web application name -->
    <img id='icon' src="./images/icon.png" alt="Logo">
    <h1>Football Managment System</h1>

    <!-- About us page link -->
    <a href="about_us.php">About Us</a>


    <?php



    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }
    try {
        global $default_dbname;
        $pdo = new PDO("mysql:host=$dbhost;dbname=$default_dbname", $dbusername, $dbuserpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
    // Fetch the user details from the database
    $email = $_SESSION['email'];
    $query = "SELECT * FROM user WHERE email = :email";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();
    $user = $statement->fetch();

    // Check if the user exists
    if (!$user) {
        die("User not found");
    }

    // Get the user's photo and name
    $photo = $user['photo'];
    $name = $user['name'];
    ?>
    <!-- User account cart (user name and photo) -->
    <figure class="user_cart">
        <a href="create_profile.php"><img id="user_icon" src="<?php echo $photo; ?>" alt="User Photo"></a>
        <figcaption>
            <?php echo $name; ?>
        </figcaption>
    </figure>

    <!-- Logout link -->
    <a href="logout.php">Logout</a>
</header>