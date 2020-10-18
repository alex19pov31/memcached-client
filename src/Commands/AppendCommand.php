<?php


namespace MemcachedClient\Commands;


/**
 * means "add this data to an existing key after existing data".
 *
 * Class AppendCommand
 * @package MemcachedClient\Commands
 */
class AppendCommand extends SetCommand
{
    /**
     * @var string
     */
    protected $baseCommand = 'append';
}