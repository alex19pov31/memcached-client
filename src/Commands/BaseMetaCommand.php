<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

abstract class BaseMetaCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var string
     */
    protected $flag;

    /**
     * @var string
     */
    protected $metaCommand = '';

    public function __construct(ClientInterface $client, string $key, string $flag)
    {
        parent::__construct($client);
        $this->key = $key;
        $this->flag = $flag;
    }

    public function getCommand(): string
    {
        return "{$this->metaCommand} {$this->key} {$this->flag}";
    }
}