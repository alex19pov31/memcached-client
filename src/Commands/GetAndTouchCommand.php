<?php


namespace MemcachedClient\Commands;

use MemcachedClient\Interfaces\ClientInterface;

/**
 * used to fetch items and update the expiration time of an existing items
 *
 * Class GetAndTouchCommand
 * @package MemcachedClient\Commands
 */
class GetAndTouchCommand extends GetCommand
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
        parent::__construct($client, $key);
        $this->key = $key;
        $this->ttl = $ttlSeconds;
    }

    public function getCommand(): string
    {
        return "gat {$this->ttl} {$this->key}\r\n";
    }
}