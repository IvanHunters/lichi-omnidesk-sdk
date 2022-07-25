<?php

declare(strict_types=1);

namespace Lichi\Omnidesk\Sdk;

use GuzzleHttp\RequestOptions;

class Cases extends Module
{

    public function get(array $filters = []): array
    {
        return self::formatting(
            $this->paginationRequest->get(
            "GET",
            "/api/cases.json",
                [
                    RequestOptions::QUERY => $filters
                ]
            ),
            'case'
        );
    }


}