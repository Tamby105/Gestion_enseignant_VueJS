<?php

// connect database
$conn = new PDO("mysql:host=localhost:3306;dbname=genseignant", "root", "");

// get all users from database sorted by latest first
$sql = "SELECT * FROM enseignant ORDER BY numens DESC";
$result = $conn->prepare($sql);
$result->execute([]);
$data = $result->fetchAll();

// send all records fetched back to AJAX
echo json_encode($data);
