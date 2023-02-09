<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Listener;

use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Server\SwooleEvent;
use Swoft\Log\Helper\CLog;

/**
 * Class RegisterServiceListener
 *
 * @since 2.0
 *
 * @Listener(event=SwooleEvent::START)
 */
class RegisterServiceListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var HttpServer $httpServer */
        $httpServer = $event->getTarget();
        exec('echo $CACHE_IP', $cache_ip);
        $service = [
            'ID'                => $cache_ip[0],
            'Name'              => 'swoft',
            'Tags'              => [
                'http'
            ],
            'Address'           => $cache_ip[0],
            'Port'              => $httpServer->getPort(),
            'Meta'              => [
                'version' => '1.0'
            ],
            'EnableTagOverride' => false,
            'Weights'           => [
                'Passing' => 10,
                'Warning' => 1
            ],
            "Check" => [
                "name"     => $cache_ip[0],
                "tcp"      => "consulclien0:8500", //服务器ip地址
                "interval" => '60s', //检查时间间隔
                "timeout"  => '10s'  //检查超时时间
            ],
        ];

        // Register
        $this->agent->registerService($service);
        exec('curl -X PUT -d \'{"max_fails\":2,"fail_timeout":10}\' http://consulclien0:8500/v1/kv/upstream/swoft_server/$CACHE_IP:$CACHE_PORT');
        CLog::info('Swoft http register service success by consul!');
    }
}
