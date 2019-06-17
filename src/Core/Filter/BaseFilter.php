<?php
/**
 * <strong>Name :  AbstractFilter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter;


use Core\EntityConfiguration\EntityConfiguration;
use Core\Entity\Error\DependencyConfigException;
use Core\Filter\FieldFilter\EqualsFieldFilter;
use Core\Filter\FieldFilter\FieldFilterInterface;
use Core\Filter\Join\BaseJoin;
use Core\Filter\Join\Error\JoinNotExistsException;
use Core\Filter\Join\JoinInterface;
use Zend\Db\Sql\Predicate\PredicateSet;

class BaseFilter implements FilterInterface
{

    protected $fieldFilters = [];
    protected $predicate = PredicateSet::OP_AND;
    protected $joins = [];
    protected $entity = null;
    protected $limit = 0;
    protected $offset = 0;

    public function getPredicate(): string
    {
        return $this->predicate;
    }

    public function setPredicate(string $predicate): FilterInterface
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * setJoin
     * @param string $dependencyName
     * @param string $alias
     * @param string|null $originEntity
     * @param string|null $originAlias
     * @return FilterInterface
     * @throws DependencyConfigException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function setJoin(string $dependencyName, string $alias = '', $originEntity = null, string $originAlias = ''): FilterInterface
    {
        $fromEntity = $originEntity ?? $this->entity;
        $dependencies = EntityConfiguration::getDependencies($fromEntity);

        if (empty($dependencies[$dependencyName])) {
            throw new DependencyConfigException();
        }

        $dependencyEntity = $dependencies[$dependencyName]["entity"];

        $joinDetails = new BaseJoin();
        $joinDetails->joinWith($fromEntity, $dependencyEntity, $alias, $originAlias);
        $this->addJoin($joinDetails);

        return $this;
    }

    public function addJoin(JoinInterface $join): FilterInterface
    {
        if (empty($join->getBaseTable())) {
            $join->setBaseTable($this->getEntity());
        }
        $this->joins[$join->getJoinTable()->getAlias()] = $join;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity(string $entity): FilterInterface
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * setEqualsField
     * @param $field
     * @param $value
     * @param string $alias
     * @return FilterInterface
     * @throws JoinNotExistsException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function setEqualsField($field, $value, string $alias = ''): FilterInterface
    {
        $entity = $this->entity;

        if (!empty($alias) && empty($this->joins[$alias])) {
            throw new JoinNotExistsException();
        } else {
            if (!empty($alias)) {
                /** @var JoinInterface $joinTable */
                $joinTable = $this->joins[$alias];
                $entity = $joinTable->getJoinTable()->getTable();
            }
        }
        if (empty($alias)) {
            $alias = EntityConfiguration::getTable($this->entity);
        }


        $equalsFilter = new EqualsFieldFilter($entity, [$field => $value], $alias);
        $this->addFilter($equalsFilter);

        return $this;
    }

    public function addFilter(FieldFilterInterface $filter): FilterInterface
    {
        $this->fieldFilters[] = $filter;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $string = "";

        foreach ($this->getFilters() as $filter) {
            $string .= $filter;
        }

        foreach ($this->getJoins() as $join) {
            $string .= ':' . $join;
        }

        return $string;
    }

    public function getFilters(): array
    {
        return $this->fieldFilters;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }

    /**
     * @inheritDoc
     */
    public function setLimit(int $limit) : FilterInterface
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return null
     */
    public function getOffset() : int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return BaseFilter
     */
    public function setOffset(int $offset): FilterInterface
    {
        $this->offset = $offset;
        return $this;
    }


}