<?php


namespace MemcachedClient\Interfaces;


interface CommandResultInterface
{
    public function getResultData();

    /**
     * @return CommandResultInterface[]
     */
    public function getList();
}