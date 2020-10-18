<?php


namespace MemcachedClient\Commands;


use MemcachedClient\Interfaces\ClientInterface;

class AuthCommand extends BaseCommand
{
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;

    public function __construct(ClientInterface $client, string $login, string $password)
    {
        parent::__construct($client);
        $this->login = $login;
        $this->password = $password;
    }

    public function getCommand(): string
    {
        $authStr = "{$this->login} {$this->password}";
        $length = strlen($authStr);
        return "set auth 0 0 {$length}\r\nusername password\r\n{$authStr}\r\n";
    }

    public function isSuccess(): bool
    {
        if (empty($this->result)) {
            return false;
        }

        $resultData = $this->result->getResultData();
        return $resultData === "STORED\r\n";
    }
}