<?php


namespace MemcachedClient\Commands;


/**
 * means "add this data to an existing key before existing data".
 *
 * Class PrependCommand
 * @package MemcachedClient\Commands
 */
class PrependCommand extends SetCommand
{
    /**
     * @var string
     */
    protected $baseCommand = 'prepend';
}