<?php
include "db.php";
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    header("Location: knoledge_base.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    } else {
        // Create a PDO connection
        global $default_dbname;
        try {
            $pdo = new PDO("mysql:host=$dbhost;dbname=$default_dbname", $dbusername, $dbuserpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the email is already registered
            $query = "SELECT * FROM $user_tablename WHERE email = :email";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                $error = "Email is already registered. Please use a different email.";
            } else {
                // Insert the user into the database
                $query = "INSERT INTO $user_tablename (name, email, password) VALUES (:username, :email, :password)";
                $statement = $pdo->prepare($query);
                $statement->bindParam(':username', $username);
                $statement->bindParam(':email', $email);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);//$hashed_password
                $statement->bindParam(':password', $password);
                $statement->execute();
                $_SESSION['email'] = $email;

                // Registration successful, redirect to login page
                header("Location: dashboard.php");
                exit();
            }
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title >Register</title>
    <link rel="stylesheet" type="text/css" href="sign.css" />

</head>
<body>
    <h1 class="sign-h1">Register</h1>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form  class="sign-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <p class="sign-p">
        Already have an account? <a href="login.php">Login</a>
    </p>
</body>
</html>
