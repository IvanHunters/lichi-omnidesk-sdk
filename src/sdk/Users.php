<?php

declare(strict_types=1);

namespace Lichi\Omnidesk\Sdk;

use GuzzleHttp\RequestOptions;

class Users extends Module
{

    public function get($userId): array
    {
        return $this->apiProvider->callMethod(
        "GET",
        "/api/users/{$userId}.json",
            []
        );
    }


}