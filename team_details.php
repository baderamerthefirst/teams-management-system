<?php
include "db.php";
session_start();
$email = $_SESSION['email'];
// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check if the team ID is provided in the URL
if (!isset($_GET['team_id'])) {
    header("Location: dashboard.php");
    exit();
}

$teamId = $_GET['team_id'];



// Fetch the team details from the database
try {
    global $default_dbname;
    $pdo = new PDO("mysql:host=$dbhost;dbname=$default_dbname", $dbusername, $dbuserpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM teams WHERE id = :team_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_id', $teamId);
    $statement->execute();

    $team = $statement->fetch();

    // Check if the team exists
    if (!$team) {
        header("Location: dashboard.php");
        exit();
    }

    // Fetch the players of the team
    $query = "SELECT * FROM players WHERE team_id = :team_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':team_id', $teamId);
    $statement->execute();

    $players = $statement->fetchAll();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the logged-in user created the team
$userCreatedTeam = ($_SESSION['email'] == $team['created_by']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Team Details</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">
            <h1>Team Details</h1>
            <p>Team Name:
                <?php echo $team['name']; ?>
            </p>
            <p>Skill Level:
                <?php echo $team['skill']; ?>
            </p>
            <p>Game Day:
                <?php echo $team['game_day']; ?>
            </p>

            <?php if ($userCreatedTeam): ?>
                <a class="edit_team_button" href="edit_team.php?team_id=<?php echo $teamId; ?>">Edit Team</a>
                <a class="delete_team_button" href="delete_team.php?team_id=<?php echo $teamId; ?>">Delete Team</a>
            <?php endif; ?>

            <h2>Players</h2>
            <?php if ($players): ?>
                <ul>
                    <?php foreach ($players as $player): ?>
                        <li>
                            <?php echo $player['name']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No players found.</p>
            <?php endif; ?>

            <?php if ($userCreatedTeam): ?>
                <h2>Add Player</h2>
                <form method="POST" action="add_player.php?team_id=<?php echo $teamId; ?>">
                    <input type="hidden" name="team_id" value="<?php echo $teamId; ?>">
                    <label>Player Name:</label>
                    <input type="text" name="player_name" required>
                    <input type="submit" value="Add Player">
                </form>
            <?php endif; ?>

            <br>
            <a href="dashboard.php">Back to Dashboard </a>
        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>