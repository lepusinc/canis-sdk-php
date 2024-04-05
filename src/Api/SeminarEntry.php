<?php
declare(strict_types=1);

namespace Canis\Api;

Class CP_API_Seminar_Entry extends ApiAbstract
{
    const API_URI_SEMINAR_ENTRY = '/seminar/entry';

    const API_URI_SEMINAR_ENTRY_WP_POST = '/seminar/entry/:wp_post_id';

    const API_URI_SEMINAR_ENTRY_NON_MEMBER = '/seminar/entry/non-member/:wp_post_id';

    const API_URI_SEMINAR_ENTRY_MEMBER = '/seminar/entry/member/:wp_post_id';

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
     * Send request to SE001: GET /v1/seminar/entry
     *
     * @return array<string,mixed>
     */
    public function getSeminarEntries(): array
    {
        return $this
            ->withTokenAdapter()
            ->get(self::API_URI_SEMINAR_ENTRY)
        ;
    }

    /**
     * Send request to SE002: GET /v1/seminar/entry/:wp_post_id
     *
     * @param string $postId
     * @return array<string,mixed>
     */
    public function getSeminarEntry(string $postId): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->get(self::API_URI_SEMINAR_ENTRY_WP_POST)
        ;
    }

    /**
     * Send request to SE003A: POST /v1/seminar/entry/non-member/:wp_post_id
     *
     * @param string $postId
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postSeminarEntryNonMember(string $postId, array $params): array
    {
        return $this
            ->withKeySecretAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->post(self::API_URI_SEMINAR_ENTRY_NON_MEMBER, $params)
        ;
    }

    /**
     * Send request to SE003B: POST /v1/seminar/entry/member/:wp_post_id
     *
     * @param string $postId
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function postSeminarEntryMember(string $postId, array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->post(self::API_URI_SEMINAR_ENTRY_MEMBER, $params)
        ;
    }

    /**
     * Send request to SE004: PUT /v1/seminar/entry/:wp_post_id
     *
     * @param string $postId
     * @param array<string,string> $params
     * @return array<string,mixed>
     */
    public function putSeminarEntry(string $postId, array $params): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->put(self::API_URI_SEMINAR_ENTRY_WP_POST, $params)
        ;
    }

    /**
     * Send request to SE005: DELETE /v1/seminar/entry/:wp_post_id
     *
     * @param string $postId
     * @return array<string,mixed>
     */
    public function deleteSeminarEntry(string $postId): array
    {
        return $this
            ->withTokenAdapter()
            ->setPlaceholders([':wp_post_id' => $postId])
            ->delete(self::API_URI_SEMINAR_ENTRY_WP_POST)
        ;
    }
}