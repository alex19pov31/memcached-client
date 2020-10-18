<?php


namespace MemcachedClient\Interfaces;


interface ClientInterface
{
    /**
     * @param $command
     * @param int $commandCount
     * @return CommandResultInterface
     */
    public function sendCommand($command, int $commandCount = 1): CommandResultInterface;

    /**
     * @return void
     */
    public function connect();

    /**
     * @return void
     */
    public function close();

    /**
     * @param $buf
     * @param int $size
     * @return int|bool
     */
    public function recv(&$buf, int $size);
}