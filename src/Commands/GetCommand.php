<?php

namespace MemcachedClient\Commands;

use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandInterface;
use MemcachedClient\Interfaces\CommandResultInterface;

class GetCommand extends BaseCommand
{
    private string $key;

    public function __construct(ClientInterface $client, string $key)
    {
        parent::__construct($client);
        $this->key = $key;
    }

    public function getCommand(): string
    {
        return "get {$this->key}\r\n";
    }

    public function hasValue(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        $resultList = explode("\r\n", $resultData);
        return count($resultList) === 3;
    }

    public function getValue()
    {
        if (empty($this->result)) {
            return null;
        }

        $resultData = $this->result->getResultData();
        $resultList = explode("\r\n", $resultData);
        if (count($resultList) < 4) {
            return null;
        }

        $result = @unserialize($resultList[1]);
        return $result !== false ? $result : $resultList[1];
    }
}