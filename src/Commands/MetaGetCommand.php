<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * generic command for retrieving key data from memcached
 *
 * Class MetaGetCommand
 * @package MemcachedClient\Commands
 */
class MetaGetCommand extends BaseMetaCommand
{
    protected $metaCommand = 'mg';
}