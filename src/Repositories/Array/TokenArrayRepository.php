<?php

namespace Frankkessler\Salesforce\Repositories\Array;

use Frankkessler\Salesforce\Repositories\TokenRepositoryInterface;
use Frankkessler\Guzzle\Oauth2\AccessToken;

class TokenArrayRepository implements TokenRepositoryInterface
{
    public $access_token = '';
    public $refresh_token = '';

    public function setAccessToken($access_token, $user_id = null)
    {
        $this->access_token = $access_token;
    }

    public function setRefreshToken($refresh_token, $user_id = null)
    {
        $this->refresh_token = $refresh_token;
    }

    public function getTokenRecord($user_id = null)
    {
        $record = new \stdClass();
        $record->access_token = $this->access_token;
        $record->refresh_token = $this->refresh_token;
        return $record;
    }

    public function setTokenRecord(AccessToken $token, $user_id = null)
    {
        $this->access_token = $token->getToken();
        $this->refresh_token = $token->getRefreshToken();
    }
}
