<?php


namespace MemcachedClient\Commands;


use MemcachedClient\CommandResult;
use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandInterface;
use MemcachedClient\Commands\MetaNoOpCommand;

class CommandChain extends BaseCommand
{
    /**
     * @var CommandInterface[]
     */
    private $commandList;

    public function __construct(ClientInterface $client, CommandInterface ...$commandList)
    {
        parent::__construct($client);
        $this->commandList = $commandList;
    }

    public function addCommand(CommandInterface $command)
    {
        $this->commandList[] = $command;
    }

    public function getCommand(): string
    {
        $delimiter = new MetaNoOpCommand($this->client);
        return implode(
            $delimiter->getCommand(),
            array_map(function (CommandInterface $command) {
                return $command->getCommand();
            }, $this->commandList)
        );
    }

    public function execute(): self
    {
        $this->result = $this->client->sendCommand(
            $this->getCommand(),
            count($this->commandList)
        );

        return $this;
    }

    /**
     * @return CommandInterface[]
     */
    public function getResults()
    {
        if (empty($this->result)) {
            return [];
        }

        $resultList = $this->result->getList();
        foreach ($this->commandList as $i => $command) {
            if (!isset($resultList[$i])) {
                break;
            }
            $command->setResult($resultList[$i]);
        }

        return $this->commandList;
    }
}