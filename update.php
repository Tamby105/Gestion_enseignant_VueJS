<?php

// connect database
$conn = new PDO("mysql:host=localhost:3306;dbname=genseignant", "root", "");

// update user name and email using his unique ID
$sql = "UPDATE enseignant SET nom = :nom, tauxhoraire = :tauxhoraire ,nbheures = :nbheures WHERE numens = :numens";
$result = $conn->prepare($sql);

$result->execute([
	":nom" => $_POST["nom"],
	":tauxhoraire" => $_POST["tauxhoraire"],
	":nbheures" => $_POST["nbheures"],
	":numens" => $_POST["numens"],
]);

// get the updated record
$sql = "SELECT * FROM enseignant WHERE numens = :numens";
$result = $conn->prepare($sql);
$result->execute(array(
    ":numens" => $_POST["numens"]
));
$data = $result->fetch();

// send the updated record back to AJAX
echo json_encode($data);