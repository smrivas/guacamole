<?php
/**
 * <strong>Name :  AbstractFieldFilterStrategy.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\FieldFilter\Strategy
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\FieldFilter\Strategy;


use Core\Filter\FieldFilter\FieldFilterInterface;

abstract class AbstractFieldFilterStrategy implements FieldFilterStrategyInterface
{
    abstract public function transform(FieldFilterInterface $filter) : array;

}