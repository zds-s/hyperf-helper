<?php
/**
 * Hyperf 助手函数文件
 */

use Hyperf\Contract\SessionInterface;
use Hyperf\Server\ServerFactory;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;
use SaTan\ComposerHelpers;

if (!function_exists('container'))
{
    /**
     * 获取容器
     * @return ContainerInterface
     */
    function container(): ContainerInterface
    {
        return \Hyperf\Utils\ApplicationContext::getContainer();
    }
}

if (!function_exists('get_class_loader'))
{
    /**
     * 获取composer助手类
     * @return ComposerHelpers
     */
    function get_class_loader(): ComposerHelpers
    {
        return new ComposerHelpers();
    }
}

if (!function_exists('check_package'))
{
    function check_package(...$package)
    {
        $class_loader = get_class_loader();
        foreach ($package as $name) {
            if (!$class_loader->hasPackage($name)) {
                throw new \Swoole\Exception(sprintf('包[%s]不存在,请执行 composer require %s', $name, $name));
            }
        }
    }
}

if (!function_exists('redis'))
{
    /**
     * 获取redis客户端
     * @return \Hyperf\Redis\Redis
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function redis(): \Hyperf\Redis\Redis
    {
        check_package('hyperf/redis');
        return container()->get(\Hyperf\Redis\Redis::class);
    }
}

if (!function_exists('get_swoole_server'))
{
    /**
     * 获取当前server实例
     * @return \Swoole\Coroutine\Server|\Swoole\Server
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function get_swoole_server()
    {
        check_package('hyperf/server');
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}

if (!function_exists('db'))
{
    /**
     * 获取db助手函数
     * @return \Hyperf\DB\DB
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function db(): \Hyperf\DB\DB
    {
        check_package('hyperf/db');
        return container()->get(\Hyperf\DB\DB::class);
    }
}

if (!function_exists('logger_factory'))
{
    /**
     * 获取日志工厂对象
     * @return \Hyperf\Logger\LoggerFactory
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function logger(): \Hyperf\Logger\LoggerFactory
    {
        check_package('hyperf/logger');
        return container()->get(\Hyperf\Logger\LoggerFactory::class);
    }
}

if (!function_exists('request'))
{
    /**
     * 获取hyperf当前声明周期中的request请求对象
     * @return \Hyperf\HttpServer\Contract\RequestInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function request(): \Hyperf\HttpServer\Contract\RequestInterface
    {
        check_package('hyperf/http-message','hyperf/http-server');
        return container()->get(\Hyperf\HttpServer\Contract\RequestInterface::class);
    }
}

if (!function_exists('response'))
{
    /**
     * 获取hyperf response对象
     * @return \Hyperf\HttpServer\Contract\ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function response(): \Hyperf\HttpServer\Contract\ResponseInterface
    {
        check_package('hyperf/http-message','hyperf/http-server');
        return container()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class);
    }
}

if (!function_exists('cache'))
{
    /**
     * 获取缓存对象
     * @return \Psr\SimpleCache\CacheInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function cache(): \Psr\SimpleCache\CacheInterface
    {
        check_package('hyperf/cache');
        return container()->get(\Psr\SimpleCache\CacheInterface::class);
    }
}

if (!function_exists('view'))
{
    /**
     * 渲染模板
     * @param string $template
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function view(string $template, array $data = [])
    {
        check_package('hyperf/view');
        $render = container()->get(RenderInterface::class);
        return $render->render($template,$data);
    }
}

if (!function_exists('session'))
{
    /**
     * 封装对session的操作|参考tp的session助手函数
     * @link https://www.kancloud.cn/manual/thinkphp6_0/1037635
     * @return bool|SessionInterface|mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Swoole\Exception
     */
    function session()
    {
        $params =  func_get_args();
        $name = $params[0] ?? null;
        $value = $params[1] ?? null;
        check_package('hyperf/session');
        $session = container()->get(SessionInterface::class);
        // 如果value不存在则就是取值
        if (func_num_args()===1)
        {
            if ($name===null)
            {
                return $session->clear();
            }
            // 如果第一个字符是？则是判断值是否存在
            if (substr($name,0,1)=='?')
            {
                return $session->has(substr($name,1));
            }
            return $session->get($name);
        }
        if (func_num_args() === 2)
        {
            // 如果value传入null 则删除name
            if ($value === null)
            {
                $session->forget($name);
            }else{
                $session->set($name,$value);
            }
        }
        return $session;
    }
}

