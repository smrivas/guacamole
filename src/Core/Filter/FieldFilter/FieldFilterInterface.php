<?php
/**
 * <strong>Name :  FieldFilter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\FieldFilter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\FieldFilter;


interface FieldFilterInterface
{
    public function getField();
    public function getFieldAlias();
    public function getValue();
    public function getConditionFn();
}