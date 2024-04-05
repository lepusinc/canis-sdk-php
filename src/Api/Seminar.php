<?php
declare(strict_types=1);

namespace Canis\Api;

use Canis\Api\Auth\Config;

Class CP_API_Seminar extends ApiAbstract
{
    const API_URI_SEMINAR = '/seminar';

    const API_URI_SEMINAR_WP_POST = '/seminar/:wp_post_id';

    const API_URI_SEMINAR_ENQUETE = '/seminar/enquete/:wp_post_id';

    /**
     * @param \Canis\Api\Auth\Config $config
     * @return $this
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        // You can also proceed for initialization additionally here.
    }

    /**
     * Send request to S001: POST /v1/seminar
     *
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postSeminar(array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->post(self::API_URI_SEMINAR, $params)
        ;
    }

    /**
     * Send request to S002: PUT /v1/seminar/:wp_post_id
     *
     * @param string $postId
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putSeminar(string $postId, array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->put(self::API_URI_SEMINAR_WP_POST, $params)
        ;
    }

    /**
     * Send request to S003: GET /v1/seminar/enquete/:wp_post_id
     *
     * @param string $postId
     * @return array<string,mixed>
     */
    public function getSeminarEnquete(string $postId): array
    {
        return $this
            ->withKeySecretAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->get(self::API_URI_SEMINAR_ENQUETE)
        ;
    }

    /**
     * Send request to S004: GET /v1/seminar/:wp_post_id
     *
     * @param string $postId
     * @return array<string,mixed>
     */
    public function getSeminar(string $postId): array
    {
        return $this
            ->withKeySecretAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->get(self::API_URI_SEMINAR_WP_POST)
        ;
    }
}