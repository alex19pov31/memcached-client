<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class CacheMemlimitCommand extends BaseCommand
{
    /**
     * @var int
     */
    private $limit;

    public function __construct(ClientInterface $client, int $limit)
    {
        parent::__construct($client);
        $this->limit = $limit;
    }

    public function getCommand(): string
    {
        return "cache_memlimit {$this->limit}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        return $resultData === "OK\r\n";
    }
}