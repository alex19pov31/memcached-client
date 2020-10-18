<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class LRUTuningCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $flag;
    /**
     * @var array
     */
    private $optionList;

    public function __construct(ClientInterface $client, string $flag, ...$optionList)
    {
        parent::__construct($client);
        $this->flag = $flag;
        $this->optionList = $optionList;
    }

    public function getCommand(): string
    {
        $options = implode(' ', $this->optionList);
        return "lru {$this->flag} {$options}\r\n";
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