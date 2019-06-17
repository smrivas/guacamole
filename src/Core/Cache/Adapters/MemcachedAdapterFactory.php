<?php
/**
 * <strong>Name :  MemcachedAdapterFactory.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Cache\Adapters;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MemcachedAdapterFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get("config");

        if (empty($config["couchbase"])) {
            return null;
        }

        $couchbaseConfig = $config["couchbase"];

        if (!isset($couchbaseConfig["user"])
            || !isset($couchbaseConfig["password"])
            || !isset($couchbaseConfig["url"])
            || !isset($couchbaseConfig["bucket"])) {
            return null;
        }

        $authenticator = new \Couchbase\ClassicAuthenticator();
        $authenticator->cluster($couchbaseConfig["user"],$couchbaseConfig["password"]);

        $cluster = new \CouchbaseCluster("couchbase://" . $couchbaseConfig["url"]);
        $cluster->authenticate($authenticator);
        $bucket = $cluster->openBucket($couchbaseConfig["bucket"]);

        $ttl = 3600 * 10;
        return new MemcachedAdapter($bucket, $ttl);
    }
}