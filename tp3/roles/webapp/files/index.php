<?php
$host = "10.75.17.67";  // IP de db1
$user = "root";
$password = "root";
$dbname = "webdb";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$result = $conn->query("SELECT name FROM users");

echo "<h1>Liste des utilisateurs :</h1>";
while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row['name']) . "</p>";
}

$conn->close();
?>
