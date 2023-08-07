<?php
include "db.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Get the file details
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_size = $_FILES['photo']['size'];
        $file_type = $_FILES['photo']['type'];

        // Define the upload directory
        $upload_dir = "uploads/";

        // Generate a unique file name
        $unique_file_name = uniqid() . "_" . $file_name;

        // Move the uploaded file to the upload directory
        $upload_path = $upload_dir . $unique_file_name;
        move_uploaded_file($file_tmp, $upload_path);
        $pdo = db_connect();
        if (!$pdo) {
            error_message("Null PDO Object");
        }
        // Update the user table with the file path
        $email = $_SESSION['email'];
        $query = "UPDATE user SET photo = :photo WHERE email = :email";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':photo', $upload_path);
        $statement->bindParam(':email', $email);
        $statement->execute();

        // Redirect the user to their profile page
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Create Profile</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">
            <h1>Upload Profile Photo</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                enctype="multipart/form-data">
                <label>Upload Photo:</label><br>
                <input type="file" name="photo" required><br><br>

                <input type="submit" value="Save">
            </form>
        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>