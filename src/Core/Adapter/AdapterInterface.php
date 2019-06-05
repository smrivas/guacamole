<?php
/**
 * <strong>Name :  Adapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */

namespace Core\Adapter;

use Core\Adapter\AdapterFactory\AdapterFactoryInterface;
use Core\Collection\CollectionInterface;
use Core\Adapter\Result\SQLResultInterface;
use Core\Entity\EntityInterface;
use Core\Filter\FilterInterface;

interface AdapterInterface
{
    /**
     * get an entity identified by its primary key.
     * It can be a single element or an array in case of composite primary key
     * @param string $entity the classname of the entity to search
     * @param int|string|array $primaryKey
     * @param null|array fieldToFetch defaults to null, fetching every Entity field
     * @return EntityInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function get(string $entity, $primaryKey, $fieldToFetch = null): ?EntityInterface;

    /**
     * Persists the data into the Entity table.
     * In case of being inTransaction it gets cached until commit gets executed
     *
     * @param EntityInterface $obj
     * @return SQLResultInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function persist(EntityInterface $obj): SQLResultInterface;

    /**
     * search
     * @param FilterInterface $filter
     * @return CollectionInterface of Entity
     * @param null|array fieldToFetch defaults to null, fetching every Entity field
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function search(FilterInterface $filter, $fieldToFetch = null): CollectionInterface;

    /**
     * Returns an Entity identified by $key with value $value
     * @param string $entity the classname of the entity to search
     * @param string $key
     * @param string|int|mixed $value
     * @param null|array fieldToFetch defaults to null, fetching every Entity field
     * @return EntityInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function findBy(string $entity, string $key, $value, $fieldToFetch = null): ?EntityInterface;

    /**
     * setAdapterFactory
     * @param AdapterFactoryInterface $adapterFactory
     * @return AdapterInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function setAdapterFactory(AdapterFactoryInterface $adapterFactory): AdapterInterface;
}