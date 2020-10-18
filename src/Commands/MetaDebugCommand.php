<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * human readable dump of all available internal metadata of an item, minus the value
 *
 * Class MetaDebugCommand
 * @package MemcachedClient\Commands
 */
class MetaDebugCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $key;

    public function __construct(ClientInterface $client, string $key)
    {
        parent::__construct($client);
        $this->key = $key;
    }

    public function getCommand(): string
    {
        return "me {$this->key}\r\n";
    }
}