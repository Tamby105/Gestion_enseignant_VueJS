<?php

// connect database
$conn = new PDO("mysql:host=localhost:3306;dbname=genseignant", "root", "");

// delete the user from database
$sql = "DELETE FROM enseignant WHERE numens = :numens";
$result = $conn->prepare($sql);
$result->execute(array(
    ":numens" => $_POST["numens"]
));

// send the response back to AJAX
echo "Done";