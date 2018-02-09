<?php

namespace agraciakuvut\KuvutPayment;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class PayProvider extends AbstractProvider
{

    const API_URL = 'https://payment.kuvut.com/api/1.0/';
    //const API_URL = 'https://test.payment.kuvut.com/api/1.0/';

    protected $api_url;
    protected $authorize_url;
    protected $token_url;

    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->api_url = self::API_URL;
        $this->authorize_url = self::API_URL . 'authorize/';
        $this->token_url = self::API_URL . 'token/';

        parent::__construct($options, $collaborators);
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