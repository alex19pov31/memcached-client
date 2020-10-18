<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandResultInterface;

/**
 * check and set operation which means "store this data but
 * only if no one else has updated since I last fetched it."
 *
 * Class CasCommand
 * @package MemcachedClient\Commands
 */
class CheckAndSetCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var int
     */
    protected $ttl;

    /**
     * @var string
     */
    protected $baseCommand = 'cas';

    private $casValue;

    public function __construct(ClientInterface $client, string $key, int $ttlSeconds, $value, $casValue)
    {
        parent::__construct($client);
        $this->key = $key;
        $this->ttl = $ttlSeconds;
        $this->value = $value;
        $this->casValue = $casValue;
    }

    public function getCommand(): string
    {
        $countBytes = strlen($this->value);
        return "{$this->baseCommand} {$this->key} 0 {$this->ttl} {$countBytes} {$this->casValue}\r\n{$this->value}\r\n";
    }
}