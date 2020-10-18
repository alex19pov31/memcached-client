<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * allows for explicit deletion of items, as well as marking items as "stale"
 * to allow serving items as stale during revalidation
 *
 * Class MetaDeleteCommand
 * @package MemcachedClient\Commands
 */
class MetaDeleteCommand extends BaseMetaCommand
{
    protected $metaCommand = 'md';
}