<?php


namespace MemcachedClient\Commands;


/**
 * means "store this data, but only if the server *doesn't* already
 * hold data for this key".
 *
 * Class AddCommand
 * @package MemcachedClient\Commands
 */
class AddCommand extends SetCommand
{
    /**
     * @var string
     */
    protected $baseCommand = 'add';
}