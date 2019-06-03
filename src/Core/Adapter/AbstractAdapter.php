<?php
/**
 * <strong>Name :  AbstractAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter;


use Core\Adapter\AdapterFactory\AdapterFactoryInterface;
use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Adapter\Hydrator\HydratorInterface;
use Core\Adapter\Result\SQLResultInterface;
use Core\Entity\EntityInterface;
use Core\Filter\FilterInterface;
use Core\Adapter\Result\Collection\CollectionInterface;

abstract class AbstractAdapter implements AdapterInterface
{


    /** @var null|\Zend\Db\Adapter\Adapter */
    protected $adapter = null;

    /** @var null|AdapterFactoryInterface */
    protected $adapterFactory = null;

    /**
     * setAdapter
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    abstract public function get(string $entity, $primaryKey, $fieldToFetch = null): ?EntityInterface;

    /**
     * @inheritdoc
     */
    abstract public function persist(EntityInterface $obj): SQLResultInterface;

    /**
     * @inheritdoc
     */
    abstract public function search(FilterInterface $filter, $fieldToFetch = null): CollectionInterface;


    /**
     * @inheritdoc
     */
    abstract public function findBy(string $entity, string $key, $value, $fieldToFetch = null): ?EntityInterface;


    /**
     * getEntityConfiguration
     * @param string $entity
     * @return EntityConfiguration
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function getEntityConfiguration(string $entity): EntityConfiguration
    {
        $configuration = new EntityConfiguration();
        $configuration->setEntity($entity);

        return $configuration;
    }

    protected function addEntityStrategyToHydrator(HydratorInterface $entityHydator, EntityConfiguration $configuration)
    {
        foreach ($configuration->getFieldMapping() as $field => $value) {
            if (is_array($value) && !empty($value["transform"])) {
                $entityHydator->addStrategy($field, $value["transform"]);
            }
        }
    }

    public function setAdapterFactory(AdapterFactoryInterface $adapterFactory): AdapterInterface
    {
        $this->adapterFactory = $adapterFactory;
        return $this;
    }

}