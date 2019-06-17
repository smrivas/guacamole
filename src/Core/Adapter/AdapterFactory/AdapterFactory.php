<?php
/**
 * <strong>Name :  AdapterFactory.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\AdapterFactory
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\AdapterFactory;


use Core\Adapter\AdapterInterface;
use Core\Cache\CacheInterface;
use Core\Entity\EntityInterface;
use Core\Entity\Error\EntityAdapterConfigException;
use Core\Entity\Error\EntityClassNotExistsException;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory implements AdapterFactoryInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;
    /** @var null|CacheInterface */
    protected $cacheAdapter = null;

    /**
     * AdapterFactory constructor.
     * @param ServiceLocatorInterface $serviceLocator
     * @param CacheInterface|null $baseCacheAdapter
     * @param null $fallBackCache
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, CacheInterface $baseCacheAdapter = null)
    {
        $this->serviceLocator = $serviceLocator;
        $this->cacheAdapter = $baseCacheAdapter;
    }


    /**
     * build
     * @param string $entity
     * @return AdapterInterface
     * @throws EntityAdapterConfigException
     * @throws EntityClassNotExistsException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function build(string $entity): AdapterInterface
    {
        /** @var EntityInterface $entity */
        if (!class_exists($entity)) {
            throw new EntityClassNotExistsException();
        }
        $entityConfiguration = $entity::getModelConfig();
        if (empty($entityConfiguration["adapter"])) {
            throw new EntityAdapterConfigException();
        }
        $adapter = $entityConfiguration["adapter"];
        /** @var AdapterInterface $adapter */
        $adapter = $this->serviceLocator->get($adapter);
        $adapter->setAdapterFactory($this);

        if (method_exists($adapter, "setCache")) {
            $cache = $this->cacheAdapter;

            if (!empty($entityConfiguration["cache"])) {
                /** @var CacheInterface $cache */
                $cache = new $entityConfiguration["cache"]["main"];
                $fallBackCache = new $entityConfiguration["cache"]["fallback"];
                $cache->setFallBack($fallBackCache);
            }

            $adapter->setCache($cache);
        }

        return $adapter;

    }
}