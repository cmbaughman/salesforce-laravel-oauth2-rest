<?php

namespace Frankkessler\Guzzle\Oauth2\GrantType;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * JSON Web Token (JWT) Bearer Token Profiles for OAuth 2.0.
 *
 * @link http://tools.ietf.org/html/draft-jones-oauth-jwt-bearer-04
 */
class JwtBearer extends GrantTypeBase
{
    public $grantType = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequired()
    {
        return array_merge(parent::getRequired(), ['jwt_private_key']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditionalOptions()
    {
        return [
            'form_params' => [
                'assertion' => $this->computeJwt(),
            ],
        ];
    }

    /**
     * Compute JWT, signing with provided private key.
     */
    protected function computeJwt()
    {
        $payload = [
            'iss' => $this->getConfig('client_id'),
            'aud' => rtrim((string)$this->getConfig('base_uri'), '/'),
            'sub' => '',
        ];

        if (isset($this->config['jwt_payload']) && is_array($this->config['jwt_payload'])) {
            $payload = array_replace($payload, $this->config['jwt_payload']);
        }

        $privateKey = InMemory::file(
            $this->config['jwt_private_key'],
            $this->config['jwt_private_key_passphrase'] ?? ''
        );

        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            $privateKey,
            InMemory::plainText('')
        );

        $now = new \DateTimeImmutable();
        $builder = $configuration->builder()
            ->issuedBy((string) $payload['iss'])
            ->permittedFor((string) $payload['aud'])
            ->relatedTo((string) $payload['sub'])
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'));

        foreach ($payload as $key => $value) {
            if (!in_array($key, ['iss', 'aud', 'sub', 'exp', 'iat'])) {
                $builder = $builder->withClaim($key, $value);
            }
        }

        $token = $builder->getToken($configuration->signer(), $configuration->signingKey());

        return $token->toString();
    }
}
