<?php

declare(strict_types=1);
require '../autoload.php';
require '../../hotelFunctions.php';

if (isset($_POST['featureName'], $_POST['featureCost'])) {
    $type = htmlspecialchars(trim($_POST['featureName']), ENT_QUOTES);
    $price = (int)htmlspecialchars(trim($_POST['featureCost']), ENT_QUOTES);

    $stmtSelect = $db->prepare("INSERT INTO features (type,price) VALUES (:type, :price)");

    $stmtSelect->bindParam(':type', $type, PDO::PARAM_STR);
    $stmtSelect->bindParam(':price', $price, PDO::PARAM_INT);

    $stmtSelect->execute();
}
