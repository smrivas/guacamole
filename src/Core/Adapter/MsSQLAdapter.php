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


use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Adapter\Error\HydratorClassNotExistsException;
use Core\Adapter\Error\HydratorResultSetException;
use Core\Adapter\Hydrator\CollectionEntityHydrator;
use Core\Adapter\Hydrator\EntityHydrator;
use Core\Adapter\Hydrator\ResultsetHydrator;
use Core\Adapter\Result\BasePersistResult;
use Core\Adapter\Result\BaseSelectResult;
use Core\Adapter\Result\Collection\CollectionInterface;
use Core\Adapter\Result\Collection\EntityCollection;
use Core\Adapter\Result\SQLResultInterface;
use Core\Entity\EntityInterface;
use Core\Filter\BaseFilter;
use Core\Filter\FieldFilter\EqualsFieldFilter;
use Core\Filter\FieldFilter\FieldFilterInterface;
use Core\Filter\FieldFilter\PrimaryKeyFieldFilter;
use Core\Filter\FieldFilter\Strategy\Error\FieldFilterStrategyNotExists;
use Core\Filter\FieldFilter\Strategy\FieldFilterStrategyInterface;
use Core\Filter\FilterInterface;
use Core\Filter\Join\JoinInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class MsSQLAdapter extends AbstractAdapter implements TransactionInterface
{
    /**
     * @var null|\Zend\Db\Adapter\Driver\ConnectionInterface
     */
    protected $transaction = null;

    const ADAPTER_TYPE = 'MSSQL';

    /**
     * get
     * @param string $entity
     * @param array|int|string $primaryKey
     * @param null $fieldToFetch
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @return EntityInterface|null
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function get(string $entity, $primaryKey, $fieldToFetch = null): ?EntityInterface
    {
        $configuration = $this->getEntityConfiguration($entity);

        $table = $configuration->getTable();

        $columns = $this->_parseFieldsToFetch($configuration, $fieldToFetch);

        $selectResult = null;

        $where = new BaseFilter();

        $pkFilter = new PrimaryKeyFieldFilter();
        $pkFilter->setEntity($entity);
        $pkFilter->setValue($primaryKey);

        $where->addFilter($pkFilter);

        $selectResult = $this->performSelect($table, $columns, $where);


        if ($selectResult) {
            $entityHydrator = new EntityHydrator();
            $entityHydrator->setAdapterFactory($this->adapterFactory);
            $this->addEntityStrategyToHydrator($entityHydrator, $configuration);

            return $entityHydrator->hydrate($entity, $selectResult->getFirst());
        }

        return null;
    }

    protected function _parseFieldsToFetch(EntityConfiguration $configuration, $fieldToFetch = null): array
    {
        $columns = [];
        $fieldsMapping = $configuration->getFieldMapping();

        if (!$fieldToFetch) {
            foreach ($fieldsMapping as $fieldName => $columnName) {
                $name = $columnName;
                if (is_array($columnName)) {
                    $name = $columnName["fieldName"];
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
     * getFieldFilterStrategy
     * @param FieldFilterInterface $fieldFilter
     * @return FieldFilterStrategyInterface
     * @throws FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function getFieldFilterStrategy(FieldFilterInterface $fieldFilter) : FieldFilterStrategyInterface
    {
        $filterClassname = (new \ReflectionClass($fieldFilter))->getShortName();
        /** @var FieldFilterStrategyInterface $strategy */
        $strategy = null;
        $defaultStrategy = 'Core\Filter\FieldFilter\Strategy\\'.$filterClassname.'Strategy';
        $adapterStrategy = $defaultStrategy.self::ADAPTER_TYPE;

        if (class_exists($adapterStrategy)) {
            $strategy = new $adapterStrategy();
        } else if (class_exists($defaultStrategy)) {
            $strategy = new $defaultStrategy();
        } else {
            throw new FieldFilterStrategyNotExists();
        }

        return $strategy;
    }

    /**
     * buildFilters
     * @param FilterInterface $filter
     * @return array
     * @throws FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function buildFilters(FilterInterface $filter) : array
    {
        $builtFilters = [];

        /** @var FieldFilterInterface $fieldFilter */
        foreach ($filter->getFilters() as $fieldFilter) {
            $strategy = $this->getFieldFilterStrategy($fieldFilter);
            $entity = $fieldFilter->getEntity();
            $builtFilters += $strategy->transform($entity, $fieldFilter);
        }

        return $builtFilters;
    }

    /**
     * performSelect
     * @param string $table
     * @param array $columns
     * @param FilterInterface|null $where
     * @param array $order
     * @param JoinInterface|null $join
     * @param int $offset
     * @param null $limit
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @throws \ReflectionException
     * @throws FieldFilterStrategyNotExists
     * @return SQLResultInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function performSelect(
        string $table,
        $columns = [],
        FilterInterface $where = null,
        $order = [],
        $offset = 0,
        $limit = null
    ) {
        $selectResult = null;

        $select = new Select($table);
        $select->columns($columns);
        $select->where($this->buildFilters($where), $where->getPredicate());

        if (count($order)) {
            $select->order($order);
        }
        $joins = $where->getJoins();
        if (!empty($joins)) {
            /** @var JoinInterface $join */
            foreach ($joins as $join) {
                $select->join($join->getJoinTable(), $join->getJoinExpresion());
            }
        }
        if ($offset) {
            $select->offset($offset);
        }
        if ($limit) {
            $select->limit($limit);
        }

        $sql = new Sql($this->adapter);

        $selectResult = $this->adapter->query(
            $sql->buildSqlString($select),
            \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
        );


        $resultsetHydrator = new ResultsetHydrator();
        return $resultsetHydrator->hydrate(BaseSelectResult::class, $selectResult);
    }

    /**
     * search
     * @param string $entity
     * @param FilterInterface $filter
     * @param null $fieldToFetch
     * @param null $offset
     * @param null $limit
     * @return CollectionInterface
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function search(
        FilterInterface $filter,
        $fieldToFetch = null,
        $offset = null,
        $limit = null
    ): CollectionInterface {
        $entity = $filter->getEntity();
        $configuration = $this->getEntityConfiguration($entity);
        $table = $configuration->getTable();

        $columns = $this->_parseFieldsToFetch($configuration, $fieldToFetch);

        $selectResult = $this->performSelect($table, $columns, $filter, $offset, $limit);

        $resultEntities = [];

        // TODO


        $entityCollectionHydrator = new CollectionEntityHydrator();
        $entityCollectionHydrator->setAdapterFactory($this->adapterFactory);
        $entityCollectionHydrator->setCollectionEntity($entity);
        $this->addEntityStrategyToHydrator($entityCollectionHydrator, $configuration);

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
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function findBy(string $entity, string $key, $value, $fieldToFetch = null): ?EntityInterface
    {
        $configuration = $this->getEntityConfiguration($entity);

        $selectResult = null;

        $table = $configuration->getTable();

        $columns = $this->_parseFieldsToFetch($configuration, $fieldToFetch);

        $whereConditions = [];

        if (isset($configuration->getFieldMapping()[$key])) {
            $whereConditions[$configuration->getFieldMapping()[$key]] = $value;
        }

        if (count($whereConditions)) {
            $where = new BaseFilter();
            foreach ($whereConditions as $field => $condition) {
                $where->addFilter(new EqualsFieldFilter($entity, [$field => $condition]));
            }
            $selectResult = $this->performSelect($table, $columns, $where);
        }

        if ($selectResult) {
            $entityHydrator = new EntityHydrator();
            $entityHydrator->setAdapterFactory($this->adapterFactory);
            $this->addEntityStrategyToHydrator($entityHydrator, $configuration);

            return $entityHydrator->hydrate($entity, $selectResult->getFirst());
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
        $configuration = $this->getEntityConfiguration($obj);

        $result = null;
        $fieldMapping = $configuration->getFieldMapping();

        $entityHydrator = new EntityHydrator();
        $entityHydrator->setAdapterFactory($this->adapterFactory);
        $newData = $entityHydrator->extract($obj, $fieldMapping);

        if (!empty($newData)) {
            $table = $configuration->getTable();
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

    public function startTransaction(): AdapterInterface
    {
        if ($this->adapter) {
            $this->transaction = $this->adapter->getDriver()->getConnection()->beginTransaction();
        }
        return $this;
    }

    public function inTransaction(): bool
    {
        return !($this->transaction === null);
    }

    public function commit()
    {
        if ($this->transaction) {
            $this->transaction->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->transaction) {
            $this->transaction->rollback();
        }
    }

    protected function getLastGeneratedValue()
    {
        if ($this->transaction) {
            return $this->transaction->getLastGeneratedValue();
        }
        return null;
    }

}