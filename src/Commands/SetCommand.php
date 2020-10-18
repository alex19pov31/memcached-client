<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * means "store this data".
 *
 * Class SetCommand
 * @package MemcachedClient\Commands
 */
class SetCommand extends BaseCommand
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
    protected $baseCommand = 'set';

    public function __construct(ClientInterface $client, string $key, int $ttlSeconds, $value)
    {
        parent::__construct($client);
        $this->key = $key;
        $this->ttl = $ttlSeconds;
        $this->value = $value;
    }

    public function getCommand(): string
    {
        $value = is_int($this->value) ? $this->value : serialize($this->value);
        $countBytes = strlen($value);

        return "{$this->baseCommand} {$this->key} 0 {$this->ttl} {$countBytes}\r\n{$value}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        return $this->result->getResultData() === "STORED\r\n";
    }
}