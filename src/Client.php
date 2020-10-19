<?php


namespace MemcachedClient;


use MemcachedClient\Commands\AuthCommand;
use MemcachedClient\Commands\MetaNoOpCommand;
use MemcachedClient\Interfaces\ClientInterface;
use MemcachedClient\Interfaces\CommandInterface;
use MemcachedClient\Interfaces\CommandResultInterface;

class Client implements ClientInterface
{
    /**
     * @var string|null
     */
    private $host;
    /**
     * @var int|null
     */
    private $port;
    /**
     * @var string|null
     */
    private $unixSocket;
    /**
     * @var int|mixed
     */
    private $timeout;
    private $socket;
    /**
     * @var AuthCommand
     */
    private $authCommand;

    protected function __construct(array $params)
    {
        $this->host = $params['host'] ?? null;
        $this->port = $params['port'] ?? null;
        $this->unixSocket = $params['unix_socket'] ?? null;
        $this->timeout = $params['timeout'] ?? 1;
    }

    public function setAuth(string $login, string $password): self
    {
        $this->authCommand = new AuthCommand($this, $login, $password);
        return $this;
    }

    public static function initFromUnixSocket(string $unixSocket, int $timeout = 1): self
    {
        return new static([
            'unix_socket' => $unixSocket,
            'timeout' => $timeout,
        ]);
    }

    public static function initFromInetSocket(string $host, int $port, int $timeout = 1): self
    {
        return new static([
            'host' => $host,
            'port' => $port,
            'timeout' => $timeout,
        ]);
    }

    public function close()
    {
        if (!empty($this->socket)) {
            socket_close($this->socket);
            $this->socket = null;
        }
    }

    public function connect()
    {
        $this->close();
        if (!empty($this->unixSocket)) {
            $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
            socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $this->timeout, 'usec' => 0]);
            socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $this->timeout, 'usec' => 0]);
            socket_connect($this->socket, $this->unixSocket);
        } else if(!empty($this->host) && $this->port > 0) {
            $data = socket_addrinfo_lookup($this->host, $this->port);
            $explain = socket_addrinfo_explain(current($data));

            $aiFamily = $explain['ai_family'];
            $aiSockType = $explain['ai_socktype'];
            $aiProtocol = $explain['ai_protocol'];

            $this->socket = socket_create($aiFamily, $aiSockType, $aiProtocol);
            socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $this->timeout, 'usec' => 0]);
            socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $this->timeout, 'usec' => 0]);
            socket_connect($this->socket, $this->host, $this->port);
        }
    }

    /**
     * @param $buf
     * @param int $size
     * @return bool|int
     */
    public function recv(&$buf, int $size)
    {
        return socket_recv($this->socket, $buf, $size, 0);
    }

    public function sendCommand($command, int $commandCount = 1): CommandResultInterface
    {
        $metaNoOpCommand = new MetaNoOpCommand($this);
        $useAuth = false;
        if ($this->authCommand instanceof CommandInterface) {
            $command = $this->authCommand->getCommand().$metaNoOpCommand->getCommand().$command;
            $useAuth = true;
            $commandCount++;
        }
        $command .= $metaNoOpCommand->getCommand();

        $this->connect();
        socket_send($this->socket, $command, strlen($command), MSG_EOR);
        return new CommandResult($this, $commandCount, null, $useAuth);
    }
}