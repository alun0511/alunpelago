<?php

declare(strict_types=1);
require '../../hotelFunctions.php';

if (isset($_POST['features'])) {
    $dbName = 'database.db';
    $db = connect($dbName);

    $ids = $_POST['features'];
    $ids = array_map('intval', $ids);
    foreach ($ids as $id) {
        $stmt = $db->prepare('DELETE FROM features WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
    }
    header('location: /hotel-manager.php');
    exit;
}
