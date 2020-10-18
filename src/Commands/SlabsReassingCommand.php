<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * used to redistribute memory once a running
 * instance has hit its limit. It might be desirable to have memory laid out
 * differently than was automatically assigned after the server started.
 *
 * Class SlabsReassingCommand
 * @package MemcachedClient\Commands
 */
class SlabsReassingCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $sourceClass;
    /**
     * @var string
     */
    private $destClass;

    public function __construct(ClientInterface $client, string $sourceClass, string $destClass)
    {
        parent::__construct($client);
        $this->sourceClass = $sourceClass;
        $this->destClass = $destClass;
    }

    public function getCommand(): string
    {
        return "slabs reassign {$this->sourceClass} {$this->destClass}\r\n";
    }
}