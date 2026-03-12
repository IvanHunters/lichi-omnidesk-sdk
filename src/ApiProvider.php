<?php


namespace Lichi\Omnidesk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Lichi\Omnidesk\Sdk\Cases;
use Lichi\Omnidesk\Sdk\CustomFields;
use Lichi\Omnidesk\Sdk\Employees;
use Lichi\Omnidesk\Sdk\Groups;
use Lichi\Omnidesk\Sdk\Users;
use RuntimeException;

class ApiProvider
{
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY_SECONDS = 5;

    private Client $client;

    /**
     * ApiProvider constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $typeRequest
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function callMethod(string $typeRequest, string $method, array $params = [])
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            sleep($attempt === 1 ? 1 : self::RETRY_DELAY_SECONDS * $attempt);

            try {
                $response = $this->client->request($typeRequest, $method, $params);
                break;
            } catch (GuzzleException $exception) {
                $lastException = $exception;

                if ($attempt < self::MAX_RETRIES) {
                    continue;
                }

                $statusCode = 'N/A';
                $responseBody = 'N/A';

                try {
                    $guzzleResponse = $exception->getResponse();
                    if ($guzzleResponse !== null) {
                        $statusCode = $guzzleResponse->getStatusCode();
                        $responseBody = (string) $guzzleResponse->getBody();
                    }
                } catch (\Throwable $e) {
                    // Response unavailable
                }

                throw new RuntimeException(sprintf(
                    "API ERROR, Method: %s %s\nHTTP Status: %s\nParams: %s\nResponse: %s\nException: %s\nAttempts: %d",
                    $typeRequest,
                    $method,
                    $statusCode,
                    json_encode($params, JSON_UNESCAPED_UNICODE),
                    $responseBody,
                    $exception->getMessage(),
                    $attempt
                ), (int) $exception->getCode(), $exception);
            }
        }

        if ($response->getStatusCode() != 200) {
            throw new RuntimeException(sprintf(
                "Http status code not 200, got %s status code, message: %s",
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }

        /** @var string $content */
        $content = $response->getBody()->getContents();

        /** @var array $response */
        $response = json_decode($content, true);

        return $response;
    }

    public function cases(){
        $self = clone $this;
        return new Cases($self);
    }

    public function groups(){
        $self = clone $this;
        return new Groups($self);
    }

    public function employees(){
        $self = clone $this;
        return new Employees($self);
    }

    public function customFields(){
        $self = clone $this;
        return new CustomFields($self);
    }

    public function users(){
        $self = clone $this;
        return new Users($self);
    }

}