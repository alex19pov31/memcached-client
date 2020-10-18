<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class MetaSetCommand extends BaseMetaCommand
{
    protected $metaCommand = 'ms';
}