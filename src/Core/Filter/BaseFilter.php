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


use Core\Filter\FieldFilter\FieldFilterInterface;
use Core\Filter\Join\Join;
use Zend\Db\Sql\Predicate\PredicateSet;

class BaseFilter implements FilterInterface
{

    protected $fieldFilters = [];
    protected $predicate = PredicateSet::OP_AND;
    protected $joins = [];


    public function getJoins(): array
    {
        return $this->joins;
    }


    public function addFilter(FieldFilterInterface $filter): FilterInterface
    {
        $this->fieldFilters[] = $filter;
        return $this;
    }

    public function getFilters(): array
    {
        $filters = [];
        /** @var FieldFilterInterface $filter */
        foreach ($this->fieldFilters as $filter) {
            $filters += $filter->getExpresion();
        }

        return $filters;

    }

    public function setPredicate(string $predicate): FilterInterface
    {
        $this->predicate = $predicate;
        return $this;
    }

    public function getPredicate(): string
    {
        return $this->predicate;
    }

    public function addJoin(Join $join): FilterInterface
    {
        // TODO: Implement addJoin() method.
    }

}