<?php
declare(strict_types=1);

namespace Canis\Api;

Class Auth extends ApiAbstract
{
    const API_URI_AUTH_LOGIN = '/auth/login';

    const API_URI_AUTH_LOGIN_ID = '/auth/login-id';

    const API_URI_AUTH_EMAIL_WHITELIST = '/auth/email/whitelist/:scope';

    const API_URI_AUTH_CREDENTIAL = '/auth/credential';

    const API_URI_AUTH_PASSWORD = '/auth/password';

    const API_URI_AUTH_PASSWORD_LEGACY = '/auth/password/legacy';

    const API_URI_AUTH_PASSWORD_RESET = '/auth/password-reset';

    const API_URI_AUTH_VERIFY_TOKEN = '/auth/verify-token';

    const WHITELIST_SCOPE_DOMAIN_ALLOWED = 'domain-allowed';

    const WHITELIST_SCOPE_EMAIL_REGISTERED = 'email-registered';

    /**
     * @param array<string,string> $config
     * @return $this
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        // You can also proceed for initialization additionally here.
    }

    /**
     * Send request to A001: POST /v1/auth/login
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postLogin(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->post(self::API_URI_AUTH_LOGIN, $params)
        ;
    }

    /**
     * Send request to A002: GET /v1/auth/login
     *
     * @return array<string,mixed>
     */
    public function getLogin(): array
    {
        return $this
            ->withTokenAdapter()
            ->get(self::API_URI_AUTH_LOGIN)
        ;
    }

    /**
     * Send request to A003: POST /v1/auth/login-id
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postLoginId(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->post(self::API_URI_AUTH_LOGIN_ID, $params)
        ;
    }

    /**
     * Send request to A004: GET /v1/auth/email/whitelist/:scope
     *
     * @param string $scope
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function getEmailWhitelist(string $scope, array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->setPlaceholders([':scope' => $scope])
            ->get(self::API_URI_AUTH_EMAIL_WHITELIST, $params)
        ;
    }

    /**
     * Determine if it's domain-allowed email
     * (Usability for getEmailWhitelist)
     *
     * @param ?string $email
     * @return boolean
     * @see getEmailWhitelist
     */
    public function isDomainAllowedEmail(?string $email): bool
    {
        $result = $this->getEmailWhitelist(
            self::WHITELIST_SCOPE_DOMAIN_ALLOWED,
            ['email' => $email]
        );

        if (!empty($result['result']) && $result['result'] === 'allowed') {
            return true;
        }
        return false;
    }

    /**
     * Determine if it's registered email
     * (Usability for getEmailWhitelist)
     *
     * @param ?string $email
     * @return boolean
     * @see getEmailWhitelist
     */
    public function isRegisteredEmail(?string $email): bool
    {
        $result = $this->getEmailWhitelist(
            self::WHITELIST_SCOPE_EMAIL_REGISTERED,
            ['email' => $email]
        );

        if (!empty($result['result']) && $result['result'] === 'allowed') {
            return true;
        }
        return false;
    }

    /**
     * Send request to A005: POST /v1/auth/credential
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postCredential(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->post(self::API_URI_AUTH_CREDENTIAL, $params)
        ;
    }

    /**
     * Send request to A006: PUT /v1/auth/password
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putPassword(array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->put(self::API_URI_AUTH_PASSWORD, $params)
        ;
    }

    /**
     * Send request to A007: PUT /v1/auth/password/legacy
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putPasswordLegacy(array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->put(self::API_URI_AUTH_PASSWORD_LEGACY, $params)
        ;
    }

    /**
     * Send request to A008: POST /v1/auth/password-reset
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postPasswordReset(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->post(self::API_URI_AUTH_PASSWORD_RESET, $params)
        ;
    }

    /**
     * Send request to A009: GET /v1/auth/verify-token
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function getVerifyToken(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->get(self::API_URI_AUTH_VERIFY_TOKEN, $params)
        ;
    }

    /**
     * Send request to A010: PUT /v1/auth/password-reset
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putPasswordReset(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->put(self::API_URI_AUTH_PASSWORD_RESET, $params)
        ;
    }

}