<?php
/**
 * <strong>Name :  APCAdapterFactory.php</strong></br>
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

class APCAdapterFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter =  new APCAdapter();
        return $adapter;
    }
}