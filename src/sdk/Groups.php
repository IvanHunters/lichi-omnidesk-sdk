<?php

declare(strict_types=1);

namespace Lichi\Omnidesk\Sdk;

use GuzzleHttp\RequestOptions;

class Groups extends Module
{

    public function get(array $filters = []): array
    {
        return self::formatting($this->paginationRequest->get(
            "GET",
            "/api/groups.json",
                [
                    RequestOptions::QUERY => $filters
                ]
            ),
            'group'
        );
    }


}