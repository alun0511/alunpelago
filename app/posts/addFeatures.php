<?php

declare(strict_types=1);
require '../autoload.php';
require '../../hotelFunctions.php';

$redirectPage = 'https://www.alune.se/alunpelago/hotel-manager.php';
$redirectPage = 'http://localhost:4000/hotel-manager.php';

if (isset($_POST['featureName'], $_POST['featureCost'])) {
    $type = htmlspecialchars(trim($_POST['featureName']), ENT_QUOTES);
    $price = (int)htmlspecialchars(trim($_POST['featureCost']), ENT_QUOTES);

    $stmtSelect = $db->prepare("INSERT INTO features (type,price) VALUES (:type, :price)");

    $stmtSelect->bindParam(':type', $type, PDO::PARAM_STR);
    $stmtSelect->bindParam(':price', $price, PDO::PARAM_INT);

    $stmtSelect->execute();

    header('location: ' . $redirectPage);
    exit;
} else {
    header('location: ' . $redirectPage);
    exit;
}
