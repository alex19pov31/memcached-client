<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class WatchCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $flag;

    public function __construct(ClientInterface $client, string $flag)
    {
        parent::__construct($client);
        $this->flag = $flag;
    }

    public function getCommand(): string
    {
        return "watch {$this->flag}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        return $resultData === 'OK';
    }
}