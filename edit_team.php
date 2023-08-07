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
try {
    //code...

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
echo $team['name'];

if (!$team) {
    error_message("Team not found");
}

// Check if the logged-in user is the creator of the team
$user_email = $_SESSION['email'];
if ($team['created_by'] != $user_email) {
    error_message("You do not have permission to edit this team");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_team_name = $_POST['team_name'];
    $new_skill_level = $_POST['skill_level'];
    $new_game_day = $_POST['game_day'];

    // Update the team's information
    $query = "UPDATE teams SET name = :team_name, skill = :skill_level, game_day = :game_day WHERE id = :team_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_name', $new_team_name);
    $statement->bindParam(':skill_level', $new_skill_level);
    $statement->bindParam(':game_day', $new_game_day);
    $statement->bindParam(':team_id', $team_id);
    $statement->execute();

    // Redirect the user to the team details page
    header("Location: team_details.php?team_id=$team_id");
    exit();
}

// Delete the team
if (isset($_POST['delete'])) {
    $query = "DELETE FROM teams WHERE id = :team_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_id', $team_id);
    $statement->execute();

    // Delete the associated players
    $query = "DELETE FROM players WHERE team_id = :team_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_id', $team_id);
    $statement->execute();

    // Redirect the user to the dashboard page
    header("Location: dashboard.php");
    exit();
}
} catch (Exception $e) {
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Team</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">
            <h1>Edit Team</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?team_id=$team_id"; ?>">
                <label>Team Name:</label><br>
                <input type="text" name="team_name" value="<?php echo $team['name']; ?>" required><br><br>
                <label>Skill Level:</label><br>
                <input type="number" name="skill_level" value="<?php echo $team['skill']; ?>" required><br><br>
                <label>Game Day:</label><br>
                <input type="date" name="game_day" value="<?php echo $team['game_day']; ?>" required><br><br>

                <input type="submit" value="Update">
            </form>
            <br>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?team_id=$team_id"; ?>">
                <input type="hidden" name="delete" value="true">
                <input type="submit" value="Delete Team">
            </form>
            <br>
            <button><a href="dashboard.php">Dashboard</a></button>

        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>