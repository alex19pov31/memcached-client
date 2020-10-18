# Memcached client

Memcached клиент, имплементирует ASCII протокол - https://github.com/memcached/memcached/blob/master/doc/protocol.txt

### Подключение через tcp и upd сокет:

```php
use MemcachedClient\Client;

$client = Client::initFromInetSocket('127.0.0.1', 11211);
```

### Подключение через unix сокет:

```php
use MemcachedClient\Client;

$client = Client::initFromUnixSocket('/usr/run/memcached.sock');
```

### Запись данных:

```php
use MemcachedClient\Client;
use MemcachedClient\Commands\SetCommand;

$client = Client::initFromInetSocket('127.0.0.1', 11211);
$setCommand = new SetCommand($client, 'some-key', 600, 'value');
$setCommand->execute()->isSuccess();
```

### Чтение данных:

```php
use MemcachedClient\Client;
use MemcachedClient\Commands\GetCommand;

$client = Client::initFromInetSocket('127.0.0.1', 11211);
$getCommand = new GetCommand($client, 'some-key');
$getCommand->execute()->getValue();
```

### Удаление данных:

```php
use MemcachedClient\Client;
use MemcachedClient\Commands\DeleteCommand;

$client = Client::initFromInetSocket('127.0.0.1', 11211);
$deleteCommand = new DeleteCommand($client, 'some-key');
$deleteCommand->execute()->isSuccess();
```

### Выполнение группы комманд:

```php
use MemcachedClient\Client;
use MemcachedClient\Commands\SetCommand;
use MemcachedClient\Commands\CommandChain;

$client = Client::initFromInetSocket('127.0.0.1', 11211);
$setCommand1 = new SetCommand($client, 'key1', 600, 'value1');
$setCommand2 = new SetCommand($client, 'key1', 600, 'value2');
$setCommand3 = new SetCommand($client, 'key1', 600, 'value3');

$commandChain = new CommandChain(
    $client, 
    $setCommand1, 
    $setCommand2, 
    $setCommand3
);

$commandResultList = $commandChain->execute()->getResults();
foreach($commandResultList as $command) {
    /**
    * @var SetCommand $command
    */
    $command->isSuccess();
}
```

# Simple cache PSR-16

```php
use MemcachedClient\CacheManager;

$client = Client::initFromInetSocket('127.0.0.1', 11211);
$cacheManager = new CacheManager($client);
$cacheManager->set('key', 'value', 600);
$cacheManager->has('key');
$cacheManager->get('key');
$cacheManager->delete('key');
$cacheManager->setMultiple([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
], 600);
$cacheManager->getMultiple([
    'key1',
    'key2',
    'key3',
]);
$cacheManager->deleteMultiple([
    'key1',
    'key2',
    'key3',
]);
$cacheManager->clear();
```