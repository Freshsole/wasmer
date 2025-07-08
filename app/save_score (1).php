<?php
// Povolit CORS pro Shopify (nahraď svou Shopify doménou)
header("Access-Control-Allow-Origin: https://cbloc.org");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// Odpověď na preflight OPTIONS požadavek
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Nastavení cookie pro cross-site použití (iframe)
setcookie('sessionid', 'abc123', [
    'expires' => time() + 3600,
    'path' => '/',
    //'domain' => '.replit.dev',  // můžeš vynechat, kvůli dynamické doméně
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

// Připojení k databázi
$host = "sql8.freesqldatabase.com";
$dbname = "sql8788580";
$user = "sql8788580";
$pass = "DpeBYwi8eV";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Chyba připojení k databázi.";
    exit;
}

// Načtení dat z JSON těla
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['username']) || !isset($data['score']) || !isset($data['time'])) {
    http_response_code(400);
    echo "Neplatná data.";
    exit;
}

$username = $conn->real_escape_string(trim($data['username']));
$score = (int)$data['score'];
$time = $conn->real_escape_string(trim($data['time'])); // Get the time data

// Uložení do tabulky userScore
$sql = "INSERT INTO userScore (username, score, time) VALUES ('$username', $score, '$time')"; // Include time column
if ($conn->query($sql) === TRUE) {
    echo "Skóre a čas uloženy!"; // Updated success message
} else {
    http_response_code(500);
    echo "Chyba při ukládání skóre a času."; // Updated error message
}

$conn->close();
?>