<?php

namespace agraciakuvut\KuvutPayment;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as HttpClientInterface;

class PayClient extends ApiClient
{
    const PAY_METHOD_REDSYS = 'redsys';
    const PAY_METHOD_AMAZON = 'amazon';

    protected $client;

    public function __construct(
        $api_key,
        $api_secret,
        HttpClientInterface $httpClient = null,
        string $customUrlApi = ''
    ) {
        $this->client = new PayProvider([
            'clientId' => $api_key,
            'clientSecret' => $api_secret
        ], [
            'httpClient' => !empty($httpClient) ? $httpClient : new Client()
        ], $customUrlApi);

        $access_token = $this->client->getAccessToken('client_credentials');
        parent::__construct(
            $access_token->getToken(),
            $access_token->getExpires(),
            $access_token->getValues(),
            $httpClient
        );
    }

    protected function getApiUrl()
    {
        return $this->client->apiUrl();
    }

    public function getList(array $data = [])
    {
        return $this->get('list', $data);
    }

    public function storePayment(array $data = [])
    {
        return $this->post('payment', $data);
    }

    public function getDataPayment(string $pay_id, $order_id)
    {
        return $this->get("checkpayment/$pay_id/$order_id", []);
    }

    public function payByToken(array $data = [])
    {
        return $this->get("paytoken", $data);
    }

}