<?php


namespace MemcachedClient\Commands;


class VersionCommand extends BaseCommand
{

    public function getCommand(): string
    {
        return "version\r\n";
    }
}