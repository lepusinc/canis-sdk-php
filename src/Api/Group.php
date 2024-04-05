<?php
declare(strict_types=1);

namespace Canis\Api;

Class Group extends ApiAbstract
{
    const API_URI_GROUP = '/group';

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
     * Send request to G001: GET /v1/group
     *
     * @return array<string,mixed>
     */
    public function getGroups(): array
    {
        return $this
            ->withKeySecretAdapter()
            ->get(self::API_URI_GROUP)
        ;
    }
}