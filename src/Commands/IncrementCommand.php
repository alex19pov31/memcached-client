<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class IncrementCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var int
     */
    private $value;

    public function __construct(ClientInterface $client, string $key, int $value)
    {
        parent::__construct($client);
        $this->key = $key;
        $this->value = $value;
    }

    public function getCommand(): string
    {
        return "incr {$this->key} {$this->value}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        return (int)$resultData > 0;
    }

    public function getValue(): int
    {
        if (empty($this->result)) {
            return 0;
        }

        $resultData = $this->result->getResultData();
        return (int)$resultData;
    }
}