<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// connect database
$conn = new PDO("mysql:host=localhost:3306;dbname=genseignant", "root", "");

// prepare insert statement
$sql = "INSERT INTO enseignant (nom, nbheures, tauxhoraire) VALUES (:nom, :nbheures, :tauxhoraire)";
$result = $conn->prepare($sql);

// execute the query
$result->execute([
	":nom" => $_POST["nom"],
	":nbheures" => $_POST["nbheures"],
	":tauxhoraire" => $_POST["tauxhoraire"],
]);


// get the latest record inserted
$sql = "SELECT * FROM enseignant WHERE nom = :nom";
$result = $conn->prepare($sql);
$result->execute(array(
    ":nom" => $_POST["nom"]
));
$data = $result->fetch();

// send the newly inserted record back to AJAX
echo json_encode($data);