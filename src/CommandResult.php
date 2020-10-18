<?php


namespace MemcachedClient;


use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandResultInterface;
use MemcachedClient\Commands\MetaNoOpCommand;

class CommandResult implements CommandResultInterface
{
    const CHUNK_SIZE = 1024;
    /**
     * @var ClientInterface
     */
    private ClientInterface $client;
    private string $resultData;
    private int $commandCount;

    public function __construct(ClientInterface $client, int $commandCount = 1, string $resultData = null)
    {
        $this->client = $client;
        $this->commandCount = $commandCount;
        $this->resultData = $resultData ?? $this->read();
    }

    /**
     * @return CommandResultInterface[]
     */
    public function getList()
    {
        $list = [];
        $dataList = explode(MetaNoOpCommand::RESPONSE, $this->resultData);
        foreach ($dataList as $data) {
            $list[] = new static($this->client, 1, $data);
        }

        return $list;
    }

    protected function read()
    {
        $data = '';
        while (true) {
            try {
                $buf = '';
                $chunkSize = $this->client->recv($buf, static::CHUNK_SIZE);
                if ($chunkSize === false) {
                    break;
                }

                $data .= $buf;
                if (substr_count($data, MetaNoOpCommand::RESPONSE) >= $this->commandCount) {
                    break;
                }
            } catch (Throwable $e) {
                break;
            }
        }

        return $data;
    }

    public function getResultData()
    {
        return $this->resultData;
    }
}