<?php
/**
 * <strong>Name :  BaseSQLAdapter.php</strong></br>
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
use Core\Adapter\Hydrator\ResultsetHydrator;
use Core\Adapter\Result\BaseSelectResult;
use Core\Adapter\Result\SQLResultInterface;
use Core\Filter\FieldFilter\FieldFilterInterface;
use Core\Filter\FieldFilter\Strategy\Error\FieldFilterStrategyNotExists;
use Core\Filter\FieldFilter\Strategy\FieldFilterStrategyInterface;
use Core\Filter\FilterInterface;
use Core\Filter\Join\JoinInterface;
use Core\Logger\LoggerInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

abstract class BaseSQLAdapter extends AbstractAdapter implements TransactionInterface, LoggerInterface
{
    const ADAPTER_TYPE = "";

    /**
     * @var null|\Zend\Db\Adapter\Driver\ConnectionInterface
     */
    protected $transaction = null;

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
        $select->columns($columns, true);
        $select->where($this->buildFilters($where), $where->getPredicate());

        if (count($order)) {
            $select->order($order);
        }
        $joins = $where->getJoins();

        if (!empty($joins)) {
            /** @var JoinInterface $join */
            foreach ($joins as $join) {
                $select->join($join->generateJoinTable(), $join->getJoinExpresion(), []);
            }
        }
        if ($offset) {
            $select->offset($offset);
        }

        if ($limit) {
            $select->limit($limit);
        }

        $sql = new Sql($this->adapter);

        $sqlSentence = $sql->buildSqlString($select);

        try {
            $selectResult = $this->adapter->query(
                $sqlSentence,
                \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE
            );
        } catch (\Exception $e) {
            \Zend\Debug\Debug::dump($e->getMessage());die;
            $this->log($e->getMessage());
        }

        $resultsetHydrator = new ResultsetHydrator();
        return $resultsetHydrator->hydrate(BaseSelectResult::class, $selectResult);
    }

    /**
     * buildFilters
     * @param FilterInterface $filter
     * @return array
     * @throws FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function buildFilters(FilterInterface $filter): array
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
     * getFieldFilterStrategy
     * @param FieldFilterInterface $fieldFilter
     * @return FieldFilterStrategyInterface
     * @throws FieldFilterStrategyNotExists
     * @throws \ReflectionException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function getFieldFilterStrategy(FieldFilterInterface $fieldFilter): FieldFilterStrategyInterface
    {
        $filterClassname = (new \ReflectionClass($fieldFilter))->getShortName();
        /** @var FieldFilterStrategyInterface $strategy */
        $strategy = null;
        $defaultStrategy = 'Core\Filter\FieldFilter\Strategy\\' . $filterClassname . 'Strategy';
        $adapterStrategy = $defaultStrategy . self::ADAPTER_TYPE;

        if (class_exists($adapterStrategy)) {
            $strategy = new $adapterStrategy();
        } else {
            if (class_exists($defaultStrategy)) {
                $strategy = new $defaultStrategy();
            } else {
                throw new FieldFilterStrategyNotExists();
            }
        }

        return $strategy;
    }

    protected function getLastGeneratedValue()
    {
        if ($this->transaction) {
            return $this->transaction->getLastGeneratedValue();
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function log($data, $level = 0)
    {
//        $backtrace = debug_backtrace();
//        array_shift($backtrace);
//        array_shift($backtrace);
//        \Zend\Debug\Debug::dump($backtrace);die;
//
//        $parentFn = reset($backtrace);
//        $function = $parentFn["function"];
//        $args = $parentFn["args"];
//
//        echo "<pre>";
//        echo $function." ";
//        echo json_encode($args);
//        echo "</pre>";
//        die;
    }


}