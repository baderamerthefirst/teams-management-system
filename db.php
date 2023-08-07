<?php


// $dbhost = 'localhost';
// $dbusername = 'as';
// $dbuserpassword = 'mypassword';
// $default_dbname = 'sss';
// $records_per_page = 3;
// $user_tablename = 'user';

// $dbhost = 'localhost';
// $dbusername = 'c8ass2_user';
// $dbuserpassword = 'skLSXvdNt9oP@';
// $default_dbname = 'c8final_db';
// $records_per_page = 3;
// $user_tablename = 'user';

$dbhost = 'sql6.freesqldatabase.com';
$dbport = '3306';
$dbusername = 'sql6630678';
$dbuserpassword = 'zkzZEsQuWk';
$default_dbname = 'sql6630678';
$records_per_page = 3;
$user_tablename = 'user';



function db_connect() {
    global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$default_dbname", $dbusername, $dbuserpassword);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}





// <?php
// $dbhost = 'sql6.freesqldatabase.com';
// $dbport = '3306';
// $dbusername = 'sql6630678';
// $dbuserpassword = 'zkzZEsQuWk';
// $default_dbname = 'sql6630678';
// $records_per_page = 3;
// $user_tablename = 'user';

// function db_connect() {
//     global $dbhost, $dbport, $dbusername, $dbuserpassword, $default_dbname;
//     try {
//         $pdo = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$default_dbname", $dbusername, $dbuserpassword);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         return $pdo;
//     } catch (PDOException $e) {
//         die("Database connection failed: " . $e->getMessage());
//     }
// }
// ?>
