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

use Core\Adapter\Result\SQLResultInterface;
use Core\Entity\Entity;
use Core\Filter\FilterInterface;

interface AdapterInterface
{
    /**
     * get an entity identified by its primary key.
     * It can be a single element or an array in case of composite primary key
     * @param string $entity the classname of the entity to search
     * @param int|string|array $primaryKey
     * @param null|array fieldToFetch defaults to null, fetching every Entity field
     * @return Entity
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function get(string $entity, $primaryKey, $fieldToFetch = null) : ?Entity;

    /**
     * Persists the data into the Entity table.
     * In case of being inTransaction it gets cached until commit gets executed
     *
     * @param Entity $obj
     * @return SQLResultInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function persist(Entity $obj) : SQLResultInterface;

    /**
     * search
     * @param string $entity the classname of the entity to search
     * @param FilterInterface $filter
     * @return array of Entity
     * @param null|array fieldToFetch defaults to null, fetching every Entity field
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function search(string $entity, FilterInterface $filter, $fieldToFetch = null) : array;

    /**
     * Returns an Entity identified by $key with value $value
     * @param string $entity the classname of the entity to search
     * @param string $key
     * @param string|int|mixed $value
     * @param null|array fieldToFetch defaults to null, fetching every Entity field
     * @return Entity
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function findBy(string $entity, string $key, $value, $fieldToFetch = null) : ?Entity;
}