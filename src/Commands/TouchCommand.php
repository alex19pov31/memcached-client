<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * used to update the expiration time of an existing item without fetching it
 *
 * Class TouchCommand
 * @package MemcachedClient\Commands
 */
class TouchCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var int
     */
    private $ttl;

    public function __construct(ClientInterface $client, string $key, int $ttlSeconds)
    {
        parent::__construct($client);
        $this->key = $key;
        $this->ttl = $ttlSeconds;
    }

    public function getCommand(): string
    {
        return "touch {$this->key} {$this->ttl}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        return $resultData === "TOUCHED\r\n";
    }
}