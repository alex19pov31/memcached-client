<?php


namespace MemcachedClient\Commands;


class FlushAllCommand extends BaseCommand
{

    public function getCommand(): string
    {
        return "flush_all\r\n";
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