<?php


namespace MemcachedClient\Commands;


/**
 * This command is useful when used with the 'q' flag and pipelining commands.
 * For example, with 'mg' the response lines are blank on miss when the 'q' flag
 * is supplied. If pipelining several 'mg's together with noreply semantics, an
 * "mn\r\n" command can be tagged to the end of the chain, which will return an
 * "MN\r\n", signalling to a client that all previous commands have been
 * processed.
 *
 * Class MetaNoOpCommand
 * @package MemcachedClient\Commands
 */
class MetaNoOpCommand extends BaseCommand
{
    const RESPONSE = "MN\r\n";

    public function getCommand(): string
    {
        return "mn\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)){
            return false;
        }

        $resultData = $this->result->getResultData();
        return $resultData === static::RESPONSE;
    }
}