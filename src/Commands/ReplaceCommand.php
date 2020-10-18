<?php


namespace MemcachedClient\Commands;


/**
 * means "store this data, but only if the server *does*
 * already hold data for this key".
 *
 * Class ReplaceCommand
 * @package MemcachedClient\Commands
 */
class ReplaceCommand extends SetCommand
{
    /**
     * @var string
     */
    protected $baseCommand = 'replace';
}