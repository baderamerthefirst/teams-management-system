<?php
include "db.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch teams from the database
try {
    $pdo = db_connect();
    if (!$pdo) {
        error_message("Null PDO Object");
    }
    $query = "SELECT * FROM teams";
    $statement = $pdo->query($query);
    $teams = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>


<head>
    <title>Dashboard</title>
</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">
            <h1>Dashboard</h1>
            <table class="teams-table">
                <tr>
                    <th>Team Name</th>
                    <th>Skill</th>
                    <th>Number of Players</th>
                    <th>Game Day</th>
                </tr>
                <?php foreach ($teams as $team): ?>
                    <tr>
                        <td><a href="team_details.php?team_id=<?php echo $team['id']; ?>"><?php echo $team['name']; ?></a>
                        </td>
                        <td>
                            <?php echo $team['skill']; ?>
                        </td>
                        <td>
                            <?php echo $team['num_players']; ?>
                        </td>
                        <td>
                            <?php echo $team['game_day']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button> <a href="create_team.php">Create New Team >>>></a></button>

        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>

