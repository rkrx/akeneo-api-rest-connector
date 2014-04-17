<?php
use Dv\Akeneo\Api\Client;
use Dv\Akeneo\Api\Credentials;

require 'config.php';

$cred = new Credentials($username, $apiKey, $salt);
$client = new Client($apiEndPoint, $cred);

// Not satisfying... I dont have any idea how to use the api properly right now
$data = $client->callJson('ecommerce/products.json');
print_r($data);