<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class GetsCommand extends GetCommand
{
    private string $key;

    public function __construct(ClientInterface $client, string $key)
    {
        parent::__construct($client);
        $this->key = $key;
    }

    public function getCommand(): string
    {
        return "gets {$this->key}\r\n";
    }

    public function getCasValue()
    {
        if (empty($this->result)) {
            return null;
        }

        $resultData = $this->result->getResultData();
        $resultList = explode("\r\n", $resultData);
        if (count($resultList) < 3) {
            return null;
        }

        $infoList = exolode(' ', current($resultList));
        return $infoList[4];
    }
}