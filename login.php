<?php
include "db.php";
session_start();



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a PDO connection
    global $default_dbname;
    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$default_dbname", $dbusername, $dbuserpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the SQL query
        $query = "SELECT * FROM $user_tablename WHERE email = :email and password = :password";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $password);
        $statement->execute();

        // Fetch the user from the database
        $user = $statement->fetch();

        // Check if the user exists and the password matches
        if ($user) {
            // Store the user_id in the session
            $_SESSION['user_id'] = $user['user_id'];

            // Store the email in the session (optional)
            $_SESSION['email'] = $email;

            // Redirect the user to the home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid credentials, show an error message
            $error = "Invalid email or password. Please try again.";
        }
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="sign.css" />
</head>

<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p>
            <?php echo $error; ?>
        </p>
    <?php endif; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <p>
        Go to signup <a href="signup.php">signup</a>
    </p>


</body>

</html>