<?php

declare(strict_types=1);


namespace Lichi\Omnidesk\Sdk;


use GuzzleHttp\RequestOptions;
use Lichi\Omnidesk\ApiProvider;

class PaginationRequest
{

    /**
     * @var ApiProvider
     */
    protected ApiProvider $apiProvider;

    public function __construct(ApiProvider $provider)
    {
        $this->apiProvider = $provider;
    }

    public function get($type, $method, $body): array
    {
        $page = 1;
        $body[RequestOptions::QUERY]['page'] = $page;
        $response = $this->apiProvider->callMethod(
            $type,
            $method,
            $body
        );

        $totalCount = $response['total_count'];
        unset($response['total_count']);

        $countInResponse = count($response);

        if ($countInResponse < $totalCount)
        {
            $responseData = $response;
            while($countInResponse < $totalCount)
            {
                $page++;
                $body[RequestOptions::QUERY]['page'] = $page;
                $response = $this->apiProvider->callMethod(
                    $type,
                    $method,
                    $body
                );

                unset($response['total_count']);

                $responseData = array_merge($responseData, $response);
                $countInResponse+= count($response);
            }
            $responseData['total_count'] = $totalCount;

            return $responseData;
        } else {
            $response['total_count'] = $totalCount;
            return $response;
        }

    }


}