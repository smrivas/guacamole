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
use Core\Entity\EntityInterface;
use Core\Entity\Error\EntityAdapterConfigException;
use Core\Entity\Error\EntityClassNotExistsException;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory implements AdapterFactoryInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /**
     * AdapterFactory constructor.
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
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

        return $adapter;

    }
}