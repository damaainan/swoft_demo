<?php

use App\Common\DbSelector;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;

use Swoft\Log\Handler\FileHandler;

return [
    // 日志配置
    'lineFormatter'      => [
        'format'     => '%datetime% [%level_name%] [%channel%] [%event%] [tid:%tid%] [cid:%cid%] [traceid:%traceid%] [spanid:%spanid%] [parentid:%parentid%] %messages%',
        'dateFormat' => 'Y-m-d H:i:s',
    ],
    'noticeHandler'      => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/notice.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'notice,info,debug,trace',
    ],
    'applicationHandler' => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/error.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'error,warning',
    ],
    'logger'             => [
        'flushRequest' => true,
        'enable'       => true,
        'json '        => false,
        'handlers'     => [
            'application' => \bean('applicationHandler'),
            'notice'      => \bean('noticeHandler'),
        ],
    ],
    'httpServer'     => [
        'class'    => HttpServer::class,
        'port'     => 18306,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],
        'on'       => [
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 3,
            'task_enable_coroutine' => true
        ]
    ],
    'httpDispatcher' => [
        // Add global http middleware
        'middlewares' => [
            // Allow use @View tag
            \Swoft\View\Middleware\ViewMiddleware::class,
        ],
    ],
    'db'             => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=ailab;host=127.0.0.1',
        'username' => 'app',
        'password' => 'appailabP*911',
        'charset' => 'utf8',
    ],
    'db2'            => [
        'class'      => Database::class,
        'dsn'        => 'mysql:dbname=test2;host=192.168.4.11',
        'username'   => 'root',
        'password'   => 'swoft123456',
        'dbSelector' => bean(DbSelector::class)
    ],
    'db2.pool'       => [
        'class'    => Pool::class,
        'database' => bean('db2')
    ],
    'db3'            => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test2;host=192.168.4.11',
        'username' => 'root',
        'password' => 'swoft123456'
    ],
    'db3.pool'       => [
        'class'    => Pool::class,
        'database' => bean('db3')
    ],
    'migrationManager' => [
        'migrationPath' => '@app/Migration',
    ],
    'redis'          => [
        'class'    => RedisDb::class,
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
    ],
    'user'           => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'      => [
        'class'  => ServicePool::class,
        'client' => bean('user')
    ],
    'rpcServer'      => [
        'class' => ServiceServer::class,
    ],
    'wsServer'       => [
        'class'   => WebSocketServer::class,
        'on'      => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
        ],
        'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
    'cliRouter'      => [
        // 'disabledGroups' => ['demo', 'test'],
    ]

];
