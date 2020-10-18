<?php


namespace MemcachedClient\Interfaces;


interface CommandInterface
{
    public function execute(): self;
    public function getCommand(): string;
    public function setResult(CommandResultInterface $result): self;
}