<?php
include "db.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if the team ID is provided
if (!isset($_GET['team_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Get the team ID
$team_id = $_GET['team_id'];

// Create a PDO connection
$pdo = db_connect();
if (!$pdo) {
    error_message("Null PDO Object");
}

// Check if the team exists
$query = "SELECT * FROM teams WHERE id = :team_id";
$statement = $pdo->prepare($query);
$statement->bindParam(':team_id', $team_id);
$statement->execute();
$team = $statement->fetch();

if (!$team) {
    error_message("Team not found");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_name = $_POST['player_name'];

    // Insert the new player
    $query = "INSERT INTO players (team_id, name) VALUES (:team_id, :player_name)";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_id', $team_id);
    $statement->bindParam(':player_name', $player_name);
    $statement->execute();


    // Update player number in the team table
    $query = "UPDATE teams SET num_players = num_players + 1 WHERE id = :team_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_id', $team_id);
    $statement->execute();


    // Redirect the user back to the team details page
    header("Location: team_details.php?team_id=$team_id");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Player</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">
            <h1>Add Player</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?team_id=$team_id"; ?>">
                <label>Player Name:</label><br>
                <input type="text" name="player_name" required><br><br>
                <input type="submit" value="Add Player">
            </form>
            <br>
            <a href="team_details.php?team_id=<?php echo $team_id; ?>">Back to Team Details</a>
        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>