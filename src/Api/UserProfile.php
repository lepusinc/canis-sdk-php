<?php
declare(strict_types=1);

namespace Canis\Api;

Class CP_API_User_Profile extends ApiAbstract
{
    const API_URI_USER_PROFILE = '/user/profile/:uuid';
    const API_URI_USER_EMAIL = '/user/email/:uuid';
    const API_URI_USER_DELIVERY_EMAIL = '/user/delivery-email/:uuid';

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
     * Send request to P001: GET /v1/user/profile/:uuid
     *
     * @param string $uuid
     * @return array<string,mixed>
     */
    public function getUserProfile(string $uuid): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':uuid' => $uuid])
            ->get(self::API_URI_USER_PROFILE)
        ;
    }

    /**
     * Send request to P002: POST /v1/user/profile/
     *
     * @param string $uuid
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postUserProfile(string $uuid, array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':uuid' => $uuid])
            ->post(self::API_URI_USER_PROFILE, $params)
        ;
    }

    /**
     * Send request to P003: PUT /v1/user/profile/:uuid
     *
     * @param string $uuid
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putUserProfile(string $uuid, array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':uuid' => $uuid])
            ->put(self::API_URI_USER_PROFILE, $params)
        ;
    }

    /**
     * Send request to P004: PUT /v1/user/delivery-email/:uuid
     *
     * @param string $uuid
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putUserDeliveryEmail(string $uuid, array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':uuid' => $uuid])
            ->put(self::API_URI_USER_DELIVERY_EMAIL, $params)
        ;
    }

    /**
     * Send request to P005: PUT /v1/user/email/:uuid
     *
     * @param string $uuid
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putUserEmail(string $uuid, array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':uuid' => $uuid])
            ->put(self::API_URI_USER_EMAIL, $params)
        ;
    }
}