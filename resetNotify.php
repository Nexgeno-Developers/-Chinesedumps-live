<?php
// resetNotify.php
session_start();
include("includes/config/classDbConnection.php");
// var_dump(include("includes/config/classDbConnection.php"));
// exit();
$objDBcon = new classDbConnection;
$mysqli   = new mysqli(
    $objDBcon->dbHost,
    $objDBcon->dbUser,
    $objDBcon->dbPass,
    $objDBcon->dbName
);
if ($mysqli->connect_error) {
    http_response_code(500);
    exit("DB connection failed");
}

// grab the userID from session
$userID = isset($_SESSION['uid']) ? intval($_SESSION['uid']) : 0;
if ($userID > 0) {
    $sql = "UPDATE temp_order
               SET notify_attempt   = NULL,
                   notify_date_time = NULL
             WHERE UserID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    
    // echo "Updated " . $stmt->affected_rows . " row(s).";
}
$mysqli->close();
