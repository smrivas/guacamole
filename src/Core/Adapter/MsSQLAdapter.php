<?php
/**
 * <strong>Name :  MsSQLAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter;


use Core\Adapter\Error\HydratorClassNotExistsException;
use Core\Adapter\Error\HydratorResultSetException;
use Core\Adapter\Field\Strategy\FieldStrategy;
use Core\Adapter\Hydrator\CollectionEntityHydrator;
use Core\Adapter\Hydrator\EntityHydrator;
use Core\Adapter\Hydrator\ResultsetHydrator;
use Core\Adapter\Result\BasePersistResult;
use Core\Adapter\Result\SQLResultInterface;
use Core\Collection\CollectionInterface;
use Core\Collection\EntityCollection;
use Core\Entity\EntityInterface;
use Core\EntityConfiguration\EntityConfiguration;
use Core\Filter\BaseFilter;
use Core\Filter\FieldFilter\EqualsFieldFilter;
use Core\Filter\FieldFilter\PrimaryKeyFieldFilter;
use Core\Filter\FilterInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class MsSQLAdapter extends BaseSQLAdapter
{
    const ADAPTER_TYPE = 'MSSQL';
    const cache_enable = false;

    /**
     * get
     * @param string $entity
     * @param array|int|string $primaryKey
     * @param null $fieldToFetch
     * @return EntityInterface|null
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @throws \Core\Filter\FieldFilter\Strategy\Error\FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function get(string $entity, $primaryKey, $fieldToFetch = null): ?EntityInterface
    {
        $cacheHash = $this->cache->generateHash();
        $selectResult = null;

        if (self::cache_enable && $this->cache->has($cacheHash)) {
            $selectResult = $this->cache->get($cacheHash);
        } else {
            $table = EntityConfiguration::getTable($entity);
            $fieldsMapping = EntityConfiguration::getFieldMapping($entity);
            $columns = $this->_parseFieldsToFetch($fieldsMapping, $fieldToFetch);

            $where = new BaseFilter();

            $pkFilter = new PrimaryKeyFieldFilter();
            $pkFilter->setEntity($entity);
            $pkFilter->setValue($primaryKey);

            $where->addFilter($pkFilter);

            $selectResult = $this->performSelect($table, $columns, $where);
            if (self::cache_enable) {
                $this->cache->set($cacheHash, $selectResult);
            }
        }

        if ($selectResult) {
            $entityHydrator = new EntityHydrator();
            $entityHydrator->setAdapterFactory($this->adapterFactory);
            $this->addEntityStrategyToHydrator($entity, $entityHydrator);

            $entity = $entityHydrator->hydrate($entity, $selectResult->getFirst());

            return $entity;
        }

        return null;
    }

    protected function _parseFieldsToFetch($fieldsMapping, $fieldToFetch = null): array
    {
        $columns = [];

        if (!$fieldToFetch) {
            foreach ($fieldsMapping as $fieldName => $columnName) {
                $name = $columnName;
                if (is_array($columnName)) {
                    $name = $columnName["fieldName"];
                    if (!empty($columnName["fieldType"]) && class_exists($columnName["fieldType"])) {
                        /** @var FieldStrategy $selectStrategy */
                        $selectStrategy = new $columnName["fieldType"];
                        $name = $selectStrategy->transform($name);
                    }
                }
                $columns[$fieldName] = $name;
            }
        } else {
            foreach ($fieldToFetch as $field) {
                if (isset($fieldsMapping[$field])) {
                    $columns[$fieldsMapping[$field]] = $field;
                } else {
                    // Field not defined in mapper
                    continue;
                }
            }
        }
        return $columns;
    }

    /**
     * @inheritDoc
     */
    protected function addLimit(Select &$select, int $limit): AdapterInterface
    {
        $select->quantifier('TOP '.$limit);
        return $this;
    }


    /**
     * search
     * @param FilterInterface $filter
     * @param null $fieldToFetch
     * @return CollectionInterface
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @throws \Core\Filter\FieldFilter\Strategy\Error\FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function search(
        FilterInterface $filter,
        $fieldToFetch = null
    ): CollectionInterface {

        $cacheHash = $this->cache->generateHash();
        $entity = $filter->getEntity();
        $table = EntityConfiguration::getTable($entity);
        $fieldsMapping = EntityConfiguration::getFieldMapping($entity);
        $columns = $this->_parseFieldsToFetch($fieldsMapping, $fieldToFetch);

        if (self::cache_enable && $this->cache->has($cacheHash)) {
            $selectResult = $this->cache->get($cacheHash);
        } else {
            $result = $this->adapter->query("SET TEXTSIZE 2147483647");
            $selectResult = $this->performSelect($table, $columns, $filter, [],$filter->getOffset(), $filter->getLimit());

            if (self::cache_enable) {
                $this->cache->set($cacheHash, $selectResult);
            }
        }
        $entityCollectionHydrator = new CollectionEntityHydrator();
        $entityCollectionHydrator->setAdapterFactory($this->adapterFactory);
        $entityCollectionHydrator->setCollectionEntity($entity);
        $this->addEntityStrategyToHydrator($entity, $entityCollectionHydrator);

        $entityCollection = $entityCollectionHydrator->hydrate(EntityCollection::class, $selectResult->toArray());

        return $entityCollection;
    }

    /**
     * findBy
     * @param string $entity
     * @param string $key
     * @param int|mixed|string $value
     * @param null $fieldToFetch
     * @return EntityInterface|null
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @throws \Core\Filter\FieldFilter\Strategy\Error\FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function findBy(string $entity, string $key, $value, $fieldToFetch = null): ?EntityInterface
    {
        $cacheHash = $this->cache->generateHash();

        $selectResult = null;
        if (self::cache_enable && $this->cache->has($cacheHash)) {
            $selectResult = $this->cache->get($cacheHash);
        } else {

            $selectResult = null;

            $table = EntityConfiguration::getTable($entity);

            $fieldsMapping = EntityConfiguration::getFieldMapping($entity);
            $columns = $this->_parseFieldsToFetch($fieldsMapping, $fieldToFetch);

            $whereConditions = [];

            if (EntityConfiguration::hasFieldMapping($entity, $key)) {
                $whereConditions[EntityConfiguration::mapField($entity, $key)] = $value;
            }

            if (count($whereConditions)) {
                $where = new BaseFilter();
                foreach ($whereConditions as $field => $condition) {
                    $where->addFilter(new EqualsFieldFilter($entity, [$field => $condition]));
                }
                $selectResult = $this->performSelect($table, $columns, $where);
                if (self::cache_enable) {
                    $this->cache->set($cacheHash, $selectResult);
                }
            }
        }

        if ($selectResult) {
            $entityHydrator = new EntityHydrator();
            $entityHydrator->setAdapterFactory($this->adapterFactory);
            $this->addEntityStrategyToHydrator($entity, $entityHydrator);

            $entity = $entityHydrator->hydrate($entity, $selectResult->getFirst());

            return $entity;
        }

        return null;
    }

    /**
     * persist
     * @param EntityInterface $obj
     * @return SQLResultInterface
     * @throws Error\HydratorResultSetException
     * @throws HydratorClassNotExistsException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function persist(EntityInterface $obj): SQLResultInterface
    {
        $result = null;

        if ($this->transaction) {
            $result = $this->persistInTransaction($obj);
        } else {
            $result = $this->persistOutOfTransaction($obj);
        }

        return $result;
    }

    protected function persistInTransaction(EntityInterface $obj): SQLResultInterface
    {

    }

    /**
     * persistOutOfTransaction
     * @param EntityInterface $obj
     * @return SQLResultInterface
     * @throws Error\HydratorResultSetException
     * @throws HydratorClassNotExistsException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function persistOutOfTransaction(EntityInterface $obj): SQLResultInterface
    {
        $sql = new Sql($this->adapter);

        $entity = get_class($obj);

        $result = null;
        $fieldMapping = EntityConfiguration::getFieldMapping($entity);

        $entityHydrator = new EntityHydrator();
        $entityHydrator->setAdapterFactory($this->adapterFactory);
        $newData = $entityHydrator->extract($obj, $fieldMapping);

        if (!empty($newData)) {
            $table = EntityConfiguration::getTable($entity);
            $id = $obj->getId();
            if (empty($id)) {
                $insert = $sql->insert($table);
                $insert->values($newData);

                $insertResult = $this->adapter->query(
                    $sql->buildSqlString($insert),
                    \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
                );

                $result = new BasePersistResult($insertResult);
            } else {
                $where = [$fieldMapping["id"] => $id];

                $update = $sql->update($table);
                $update->set($newData);
                $update->where($where);

                $updateResult = $this->adapter->query(
                    $sql->buildSqlString($update),
                    \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
                )->execute();

                $resultsetHydrator = new ResultsetHydrator();
                $result = $resultsetHydrator->hydrate(BasePersistResult::class, $updateResult);
            }
        }

        return $result;
    }


}