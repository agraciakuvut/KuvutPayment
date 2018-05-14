<?php

namespace agraciakuvut\KuvutPayment;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class PayProvider extends AbstractProvider
{

    protected $api_url;
    protected $authorize_url;
    protected $token_url;

    public function __construct(array $options = [], array $collaborators = [], $customApiUrl = '')
    {
        if ($customApiUrl) {
            $this->api_url = $customApiUrl;
        } else {
            $this->api_url = 'https://payment.kuvut.com/api/1.0/';
        }

        $this->authorize_url = $this->api_url . 'authorize/';
        $this->token_url = $this->api_url . 'token/';

        parent::__construct($options, $collaborators);
    }

    public function apiUrl()
    {
        return $this->api_url;
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->authorize_url;
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->token_url;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return '';
    }

    protected function getDefaultScopes()
    {
        return ['basic'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            $message = $data['message'] ?? $data['error'];
            throw new \Exception($message, -1);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return null;
    }
}