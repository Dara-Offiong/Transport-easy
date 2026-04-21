<?php

// Load stations
$stations = [];
$file = fopen(__DIR__ . "/../zones-d-arrets.csv", "r");
while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
    $stations[] = $data;
}
fclose($file);

// Load elevator status
$elevatorStatus = [];
$elevatorFile = fopen(__DIR__ . "/../etat-des-ascenseurs.csv", "r");
while (($data = fgetcsv($elevatorFile, 1000, ";")) !== FALSE) {
    $elevatorStatus[] = $data;
}
fclose($elevatorFile);

// Load view
require __DIR__ . "/../source/views/home.php";