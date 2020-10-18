<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandInterface;
use MemcachedClient\Interfaces\CommandResultInterface;

abstract class BaseCommand implements CommandInterface
{
    /**
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * @var CommandResultInterface
     */
    protected $result;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    abstract public function getCommand(): string;

    public function execute(): self
    {
        $this->result = $this->client->sendCommand(
            $this->getCommand()
        );

        return $this;
    }

    public function setResult(CommandResultInterface $result): self
    {
        $this->result = $result;
        return $this;
    }
}