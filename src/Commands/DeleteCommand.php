<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandResultInterface;

class DeleteCommand extends BaseCommand
{
    private string $key;

    public function __construct(ClientInterface $client, string $key)
    {
        parent::__construct($client);
        $this->key = $key;
    }

    public function getCommand(): string
    {
        return "delete {$this->key}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        return $resultData === "DELETED\r\n";
    }
}