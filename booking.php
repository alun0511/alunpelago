<?php

declare(strict_types=1);

require __DIR__ . '/hotelFunctions.php';
require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

$homePage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (isset($_POST['name'], $_POST['arrival'], $_POST['departure'], $_POST['room'], $_POST['transfercode'])) {


    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $arrivalDate = htmlspecialchars($_POST['arrival'], ENT_QUOTES);
    $departureDate = htmlspecialchars($_POST['departure'], ENT_QUOTES);
    $roomID = htmlspecialchars($_POST['room'], ENT_QUOTES);
    $transfercode = htmlspecialchars($_POST['transfercode'], ENT_QUOTES);

    //Get info about the chosen features
    if (!empty($_POST['features'])) {
        $chosenFeatures = $_POST['features'];
        $chosenFeatures = array_map('intval', $chosenFeatures);
        $featureCost = getFeatureCost($chosenFeatures);
    } else {
        $chosenFeatures = array();
        $featureCost = 0;
    }


    $totalCost = countTotalCost($arrivalDate, $departureDate, $roomID, $featureCost);

    $result = transferCodeValidator($transfercode, $totalCost);

    if ($result !== true) $message['error'] = $result;
    $result = testDate($name, $arrivalDate, $departureDate, $roomID);

    if ($result !== true) $message['error'] = $result;

    $result = deposit($transfercode);

    if ($result !== true) $message['error'] = $result;

    if (isset($message['error'])) {
        echo json_encode($message);
        die();
    }

    insertDate($name, $arrivalDate, $departureDate, $roomID);

    foreach ($chosenFeatures as $feature) {
        $data = getChosenFeatures($feature);
        $postFeatures[] = $data;
    }
    die(var_dump($postFeatures));
    $bookingResponse = [
        "island" => "Pelagon",
        "hotel" => "Moster Dagnys",
        "arrival_date" => $arrivalDate,
        "departure_date" => $departureDate,
        "total_cost" => $totalCost,
        "stars" => "1",
        "features" => $postFeatures,
        "addtional_info" => "Thank you for making the right choice by staying at Moster Dagnys. We hope you will enjoy your stay."
    ];

    $vacation = $bookingResponse;

    $logbook = file_get_contents('logbook.json');
    $tempArray = json_decode($logbook, true);
    array_push($tempArray, $vacation);
    $jsonData = json_encode($tempArray);
    file_put_contents('logbook.json', $jsonData);

    echo json_encode($bookingResponse);

    // file_put_contents('logbook.json', json_encode($bookingResponse, FILE_APPEND));
}
