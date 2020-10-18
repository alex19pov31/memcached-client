<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class StatsCommand extends BaseCommand
{
    /**
     * @var array
     */
    private $args;

    public function __construct(ClientInterface $client, ...$args)
    {
        parent::__construct($client);
        $this->args = $args;
    }

    public function getCommand(): string
    {
        if (empty($this->args)) {
            return "stats\r\n";
        }

        $args = implode(' ', $this->args);
        return "stats {$args}\r\n";
    }
}