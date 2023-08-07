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
    $teamName = $_POST['team_name'];
    $teamSkill = $_POST['team_skill'];
    $gameDay = $_POST['game_day'];

    // Insert the new team into the database
    try {
        global $default_dbname;
        $pdo = new PDO("mysql:host=$dbhost;dbname=$default_dbname", $dbusername, $dbuserpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO teams (name, skill, game_day,created_by) VALUES (:name, :skill, :game_day,:created_by)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':name', $teamName);
        $statement->bindParam(':skill', $teamSkill);
        $statement->bindParam(':game_day', $gameDay);
        $statement->bindParam(':created_by', $_SESSION['email']);
        $statement->execute();

        // Redirect the user to the dashboard page
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create New Team</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">
            <h1>Create New Team</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label>Team Name:</label><br>
                <input type="text" name="team_name" required><br><br>
                <label>Skill Level:</label><br>
                <input type="number" name="team_skill" min="1" max="5" required><br><br>
                <label>Game Day:</label><br>
                <input type="date" name="game_day" required><br><br>

                <input type="submit" value="Create Team">
            </form>
            <br>
            <button> <a href="dashboard.php">Back to Dashboard >>></a></button>
        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>