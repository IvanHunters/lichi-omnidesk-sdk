<?php

declare(strict_types=1);


namespace Lichi\Omnidesk\Sdk;


use Lichi\Omnidesk\ApiProvider;

class Module
{

    /**
     * @var ApiProvider
     */
    protected ApiProvider $apiProvider;
    /**
     * @var PaginationRequest
     */
    protected PaginationRequest $paginationRequest;

    public function __construct(ApiProvider $provider)
    {
        $this->apiProvider = $provider;
        $this->paginationRequest = new PaginationRequest($provider);
    }

    protected static function formatting(array $fields, string $fieldName)
    {
        $formattedFields['count'] = $fields['total_count'];
        $fieldList = [];
        unset($fields['total_count']);

        foreach ($fields as $fieldData)
        {
            $field = $fieldData[$fieldName];
            $fieldList[] = $field;
        }
        $formattedFields['data'] = $fieldList;
        return $formattedFields;
    }

}