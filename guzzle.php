<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;


$client = new Client([
    'base_uri' => 'https://www.yrgopelago.se/centralbank',
    'timeout' => 10.0,
]);

$request = new Request('POST', 'https://www.yrgopelago.se/centralbank/');

$response = $client->send($request, [
    'headers' => [
        'transferCode' => '3fc91420-7bf4-4c1e-ab91-96cfd3ccd741 ', 'totalcost' => 10
    ]
]);

$response_body = (string)$response->getBody();
?>
<pre><?php print_r(json_decode($response_body)); ?></pre><?php


// rune transferCode: 3fc91420-7bf4-4c1e-ab91-96cfd3ccd741  	{'transferCode': 'the-transfercode', 'totalcost': 10}

// // Centralbank Website

// $response = $client->request('GET', '/');

// //A list of the islands and the hotels

// $response = $client->get('/islands');


// //Check if a transferCode is valid and unused

// $response = $client->request('POST', '/transferCode');

// //Consume the transferCode into money

// $response = $client->request('POST', '/deposit');
