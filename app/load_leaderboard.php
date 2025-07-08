<?php
// Allow CORS for Shopify (replace with your actual domain)
header("Access-Control-Allow-Origin: https://cbloc.org");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database connection settings
$host = "sql8.freesqldatabase.com";
$dbname = "sql8788580";
$user = "sql8788580";
$pass = "DpeBYwi8eV";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Fetch top 5 scores (ignore usernames and times)
$sql = "SELECT score FROM userScore ORDER BY score DESC LIMIT 5";
$result = $conn->query($sql);

$scores = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scores[] = (int)$row['score'];
    }
}

// Return as JSON
echo json_encode($scores);

$conn->close();
?>