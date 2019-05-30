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


use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Adapter\Hydrator\HydratorInterface;
use Core\Adapter\Result\SQLResultInterface;
use Core\Entity\Entity;
use Core\Filter\FilterInterface;

abstract class AbstractAdapter implements AdapterInterface
{


    /** @var null|\Zend\Db\Adapter\Adapter */
    protected $adapter = null;

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
    abstract public function get(string $entity, $primaryKey, $fieldToFetch = null) : ?Entity;
    /**
     * @inheritdoc
     */
    abstract public function persist(Entity $obj): SQLResultInterface;
    /**
     * @inheritdoc
     */
    abstract public function search(string $entity, FilterInterface $filter, $fieldToFetch = null): array;
    /**
     * @inheritdoc
     */
    abstract public function findBy(string $entity,string $key, $value, $fieldToFetch = null): ?Entity;


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

}