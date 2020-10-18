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
    private $client;
    /**
     * @var string
     */
    private $resultData;
    /**
     * @var int
     */
    private $commandCount;
    /**
     * @var bool
     */
    private $useAuth;

    public function __construct(
        ClientInterface $client,
        int $commandCount = 1,
        string $resultData = null,
        bool $useAuth = false
    )
    {
        $this->client = $client;
        $this->commandCount = $commandCount;
        $this->resultData = $resultData ?? $this->read();
        $this->useAuth = $useAuth;
    }

    /**
     * @return CommandResultInterface[]
     */
    public function getList()
    {
        $list = [];
        $dataList = explode(MetaNoOpCommand::RESPONSE, $this->resultData);
        foreach ($dataList as $i => $data) {
            if ($this->useAuth && $i === 0) {
                continue;
            }

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

    public function isSuccessAuth(): bool
    {
        $resultDataList = explode(MetaNoOpCommand::RESPONSE, $this->resultData);
        if (!$this->useAuth || count($resultDataList) < 2) {
            return false;
        }

        return current($resultDataList) === 'STORED\r\n';
    }

    public function getResultData()
    {
        $resultDataList = explode(MetaNoOpCommand::RESPONSE, $this->resultData);
        if (!$this->useAuth || count($resultDataList) < 2) {
            return current($resultDataList);
        }

        return $resultDataList[1];
    }
}