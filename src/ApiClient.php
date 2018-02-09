<?php

namespace agraciakuvut\KuvutPayment;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as HttpClientInterface;

abstract class ApiClient
{

    private $access_token;
    private $expires;
    private $values;
    private $client;

    abstract protected function getApiUrl();

    public function __construct(string $accessToken, $expires = null, $values = [], HttpClientInterface $httpClient = null)
    {
        $this->access_token = $accessToken;
        $this->expires = $expires;
        $this->values = $values;
        $this->client = !empty($httpClient) ? $httpClient : new Client();
    }

    protected function getUrlEndpoint($endpoint)
    {
        return $this->getApiUrl() . "/{$endpoint}/";
    }

    protected function get(string $endpoint, array $data)
    {
        return $this->request('GET', $endpoint, $data);
    }

    protected function post(string $endpoint, array $data)
    {
        return $this->request('POST', $endpoint, $data);
    }

    protected function put(string $endpoint, array $data)
    {
        return $this->request('PUT', $endpoint, $data);
    }

    protected function delete(string $endpoint, array $data)
    {
        return $this->request('DELETE', $endpoint, $data);
    }

    protected function request(string $method, string $endpoint, array $data)
    {
        $response = $this->client->request($method, $this->getApiUrl() . $endpoint, [
            'headers' => $this->getHeaders(),
            'body'    => json_encode($data)
        ]);

        if ($response->getStatusCode() != 200) {
            return [
                'error'      => true,
                'statuscode' => $response->getStatusCode(),
                'message'    => $response->getReasonPhrase()
            ];
        }

        return $response->getBody()->getContents();

    }

    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->access_token
        ];
    }

}