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

// Check if the logged-in user is the creator of the team
$user_email = $_SESSION['email'];
if ($team['created_by'] != $user_email) {
    error_message("You do not have permission to delete this team");
}



// Delete the associated players
$query = "DELETE FROM players WHERE team_id = :team_id";
$statement = $pdo->prepare($query);
$statement->bindParam(':team_id', $team_id);
$statement->execute();
// Delete the team
$query = "DELETE FROM teams WHERE id = :team_id";
$statement = $pdo->prepare($query);
$statement->bindParam(':team_id', $team_id);
$statement->execute();



// Redirect the user to the dashboard page
header("Location: dashboard.php");
exit();
?>
