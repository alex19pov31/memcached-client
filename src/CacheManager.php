<?php


namespace MemcachedClient;


use MemcachedClient\Commands\CommandChain;
use MemcachedClient\Commands\DeleteCommand;
use MemcachedClient\Commands\FlushAllCommand;
use MemcachedClient\Commands\GetCommand;
use MemcachedClient\Commands\SetCommand;
use MemcachedClient\Interfaces\ClientInterface;
use Psr\SimpleCache\CacheInterface;

class CacheManager implements CacheInterface
{
    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function get($key, $default = null)
    {
        $getCommand = new GetCommand($this->client, $key);
        return $getCommand->execute()->getValue() ?? $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $setCommand = new SetCommand($this->client, $key, (int)$ttl, $value);
        return $setCommand->execute()->isSuccess();
    }

    public function delete($key)
    {
        $deleteCommand = new DeleteCommand($this->client, $key);
        return $deleteCommand->execute()->isSuccess();
    }

    public function clear()
    {
        $flushCommand = new FlushAllCommand($this->client);
        return $flushCommand->execute()->isSuccess();
    }

    public function getMultiple($keys, $default = null)
    {
        $values = [];
        $commandList = [];
        foreach ($keys as $key) {
            $commandList[] = new GetCommand($this->client, $key);
        }

        $commandChain = new CommandChain($this->client, ...$commandList);
        $commandResultList = $commandChain->execute()->getResults();
        foreach ($commandResultList as $command) {
            /**
             * @var GetCommand $command
             */
            $values[] = $command->getValue();
        }

        return $values;
    }

    public function setMultiple($values, $ttl = null)
    {
        $commandList = [];
        foreach ($values as $key => $value) {
            $commandList[] = new SetCommand($this->client, $key, $ttl, $value);
        }

        $commandChain = new CommandChain($this->client, ...$commandList);
        $commandResultList = $commandChain->execute()->getResults();
        foreach ($commandResultList as $command) {
            /**
             * @var SetCommand $command
             */
            if (!$command->isSuccess()) {
                return false;
            }
        }

        return true;
    }

    public function deleteMultiple($keys)
    {
        $commandList = [];
        foreach ($keys as $key) {
            $commandList[] = new DeleteCommand($this->client, $key);
        }

        $commandChain = new CommandChain($this->client, ...$commandList);
        $commandResultList = $commandChain->execute()->getResults();
        foreach ($commandResultList as $command) {
            /**
             * @var DeleteCommand $command
             */
            if (!$command->isSuccess()) {
                return false;
            }
        }

        return true;
    }

    public function has($key)
    {
        $getCommand = new GetCommand($this->client, $key);
        return $getCommand->execute()->hasValue();
    }
}