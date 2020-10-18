<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

/**
 * The slabs automove command enables a background thread which decides on its
 * own when to move memory between slab classes. Its implementation and options
 * will likely be in flux for several versions. See the wiki/mailing list for
 * more details.
 *
 * Class SlabsAutomoveCommand
 * @package MemcachedClient\Commands
 */
class SlabsAutomoveCommand extends BaseCommand
{
    /**
     * @var int
     */
    private $value;

    public function __construct(ClientInterface $client, int $value)
    {
        parent::__construct($client);
        $this->value = $value;
    }

    public function getCommand(): string
    {
        return "slabs automove {$this->value}\r\n";
    }
}