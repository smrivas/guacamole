<?php
/**
 * <strong>Name :  AbstractFieldFilter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\FieldFilter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\FieldFilter;


abstract class AbstractFieldFilter implements FieldFilterInterface
{
    protected $field;
    protected $value;
    protected $fieldAlias;
    protected $conditionFn;

    abstract public function getExpresion(): array;
}