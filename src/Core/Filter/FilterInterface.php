<?php
/**
 * <strong>Name :  Filter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter;


use Core\Entity\EntityInterface;
use Core\Filter\FieldFilter\FieldFilterInterface;
use Core\Filter\Join\JoinInterface;

interface FilterInterface
{

    /**
     * Adds a fieldFilter to the array
     * @param FieldFilterInterface $filter
     * @return FilterInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function addFilter(FieldFilterInterface $filter): FilterInterface;

    /**
     * Returns the array of filters configured
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getFilters(): array;

    /**
     * Returns the type of combination for the filters
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getPredicate(): string;

    /**
     * Adds a Join to the filter conditions
     * @param JoinInterface $join
     * @return FilterInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function addJoin(JoinInterface $join): FilterInterface;

    public function getJoins(): array;

    public function setPredicate(string $predicate): FilterInterface;

    public function getEntity() : string;

    public function setJoin(string $dependencyName, string $alias = '') : FilterInterface;

    public function setEqualsField($field, $value, string $alias = '') : FilterInterface;


}