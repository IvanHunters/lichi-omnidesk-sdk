<?php

declare(strict_types=1);

namespace Lichi\Omnidesk\Sdk;

use GuzzleHttp\RequestOptions;

class CustomFields extends Module
{

    public function get(array $filters = []): array
    {
        return self::formatting($this->paginationRequest->get(
            "GET",
            "/api/custom_fields.json",
                [
                    RequestOptions::QUERY => $filters
                ]
            ),
            'custom_field'
        );
    }


}