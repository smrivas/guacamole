<?php
/**
 * <strong>Name :  AdapterFactoryFactory.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\AdapterFactory
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\AdapterFactory;


use Core\Cache\Adapters\AdapterInterface;
use Core\Cache\CacheInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var CacheInterface $baseCacheAdapter */
        $baseCacheAdapter = $serviceLocator->get("Core\BaseCacheAdapter");
        /** @var AdapterInterface $fallBackCache */
        $fallBackCache = $serviceLocator->get("Core\MemcachedAdapter");

        $baseCacheAdapter->setFallBack($fallBackCache);

        return new AdapterFactory($serviceLocator, $baseCacheAdapter);
    }
}