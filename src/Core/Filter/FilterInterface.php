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


use Core\Filter\FieldFilter\FieldFilterInterface;
use Core\Filter\Join\Join;

interface FilterInterface
{

    /**
     * Adds a fieldFilter to the array
     * @param FieldFilterInterface $filter
     * @return FilterInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function addFilter(FieldFilterInterface $filter) : FilterInterface;

    /**
     * Returns the array of filters configured
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getFilters() : array;

    /**
     * Returns the type of combination for the filters
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getPredicate() : string;

    /**
     * Adds a Join to the filter conditions
     * @param Join $join
     * @return FilterInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function addJoin(Join $join) : FilterInterface;

    public function getJoins() : array;

    public function setPredicate(string $predicate): FilterInterface;
}