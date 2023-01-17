<?php

declare(strict_types=1);
require '../autoload.php';
require '../../vendor/autoload.php';
//Booting up dotenv to match the given information by the user to the set data in our .env file
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../../');
$dotenv->load();
//create an array for eventual errors to be stored in
$_SESSION['errors'] = [];
//Set our redirect page to a variable if it would need to be swapped to something else in the future
$redirectPage = 'http://localhost:4000/hotel-manager.php';

//Compare given username and password to data in .env
if (isset($_POST['user'], $_POST['password'])) {
    $username = htmlspecialchars(trim($_POST['user']), ENT_QUOTES);
    $password = htmlspecialchars((trim($_POST['password'])), ENT_QUOTES);

    if ($_ENV['USER_NAME'] !== $username) {
        //If username is wrong we send the user back to the hotel-manager page
        $message = 'Incorrect username';
        array_push($_SESSION['errors'], $message);
        header('location: ' . $redirectPage);
        exit;
    } else {
        if ($_ENV['API_KEY'] !== $password) {
            $message = 'Incorrect password';
            array_push($_SESSION['errors'], $message);
            header('location: ' . $redirectPage);
            exit;
        } else {
            //Set the session variable user. This will count as our verification
            $_SESSION['user'] = [
                'name' => $username
            ];
            header('Location: ' . $redirectPage);
            exit;
        }
    }
}
