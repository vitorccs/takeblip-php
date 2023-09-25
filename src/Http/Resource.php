<?php

namespace TakeBlip\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use TakeBlip\Enums\HttpMethod;
use TakeBlip\Exceptions\HttpClientException;
use TakeBlip\Exceptions\HttpServerException;
use TakeBlip\Exceptions\TakeBlipException;
use TakeBlip\Factories\ClientFactory;
use TakeBlip\Helpers\StringHelper;

class Resource
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @param string|null $apiKey
     * @param int|null $timeout
     * @param string|null $apiUrl
     * @throws TakeBlipException
     */
    public function __construct(?string $apiKey = null,
                                ?int    $timeout = null,
                                ?string $apiUrl = null)
    {
        $this->client = ClientFactory::create($apiKey, $timeout, $apiUrl);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return object|null
     * @throws TakeBlipException
     */
    public function post(string $endpoint, array $data = []): ?object
    {
        $this->addUuid($data);
        return $this->request(HttpMethod::POST, $endpoint, ['json' => $data]);
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @throws HttpClientException
     * @throws HttpServerException
     * @throws TakeBlipException
     */
    private function request(HttpMethod $method, string $endpoint, array $options = []): ?object
    {
        try {
            $response = $this->client->request($method->value, $endpoint, $options);
        } catch (\Throwable $e) {
            $response = method_exists($e, 'getResponse')
                ? $e->getResponse()
                : null;
        }

        return $this->checkResponse($response, $e ?? null);
    }

    /**
     * @param ResponseInterface|null $response
     * @return object|null
     */
    private function getData(ResponseInterface $response = null): ?object
    {
        if (is_null($response)) return null;

        $content = $response->getBody()->getContents();

        return json_decode($content);
    }

    /**
     * Look for errors inside the response object and throw an Exception
     *
     * Take Blip API is not in accordance with HTTP REST standard since failed
     * HTTP request are always responded as successful (200 HTTP Status code)
     *
     * @throws HttpServerException
     * @throws HttpClientException
     * @throws TakeBlipException
     */
    private function checkResponse(ResponseInterface $response = null,
                                   \Throwable        $e = null): ?object
    {
        $data = $this->getData($response);
        $error = $this->checkForErrors($data);

        if (is_null($error) && is_null($e)) {
            return $data;
        }

        $code = $error['code'] ?? $e->getCode();
        $message = $error['description'] ?? $e->getMessage();
        $httpCode = $response?->getStatusCode();

        if ($e instanceof ClientException || !is_null($error)) {
            throw new HttpClientException($message, $httpCode, $code);
        }

        if ($e instanceof ServerException) {
            throw new HttpServerException($message, $httpCode, $code);
        }

        throw new TakeBlipException($message, $code);
    }

    /**
     * Attempt to get error message from response body
     *
     * The errors may be in two different formats:
     *   202 { "status": "failure", "reason": { "code", "description" }}
     *   4XX { "code", "description" }
     *
     * @param object|null $data
     */
    private function checkForErrors(object $data = null): ?array
    {
        $hasFailedStatus = ($data->status ?? null) === 'failure';
        $hasFailedDescription = !empty($data->description ?? null);

        if (!$hasFailedStatus && !$hasFailedDescription) {
            return null;
        }

        $reason = $data->reason ?? null;
        $code = $data->code ?? $reason->code ?? 0;
        $description = $data->description ?? $reason->description ?? null;

        if (empty($description)) {
            return null;
        }

        return [
            'code' => $code,
            'description' => $description
        ];
    }

    /**
     * @param array $data
     */
    private function addUuid(array &$data)
    {
        if (!empty($data['id'] ?? null)) return;

        $data['id'] = StringHelper::uuid4();
    }
}
