<?php
declare(strict_types=1);

namespace Canis\Api;

use Canis\Api\Auth\Config;

Class SeminarPayment extends ApiAbstract
{
    const API_URI_SEMINAR_PAYMENT = '/seminar/payment/:wp_post_id';

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
     * Send request to SP001: POST /v1/seminar/payment/:wp_post_id
     *
     * @param string $postId
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postSeminarPayment(string $postId, array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->post(self::API_URI_SEMINAR_PAYMENT, $params)
        ;
    }
}