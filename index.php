<?php

include "vendor/autoload.php";

use Lichi\Omnidesk\ApiProvider;
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => "https://support-equeo.omnidesk.ru",
    'verify' => false,
    'timeout'  => 30.0,
    'auth' => [getenv('API_EMAIL'), getenv('API_KEY')],
]);

$apiProvider = new ApiProvider($client);

$cases = $apiProvider->cases()->get([
    'from_time' => '2022-07-22'
]);

//$groups = $apiProvider->groups()->get();
//$employees = $apiProvider->employees()->get();
//$customFields = $apiProvider->customFields()->get();
