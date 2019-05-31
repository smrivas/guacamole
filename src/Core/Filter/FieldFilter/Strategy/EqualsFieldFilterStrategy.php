<?php
/**
 * <strong>Name :  EqualsFieldFilterStrategy.php</strong></br>
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

class EqualsFieldFilterStrategy extends AbstractFieldFilterStrategy
{
    public function transform(FieldFilterInterface $filter) : array
    {
        return [$filter->getField() => $filter->getValue()];
    }

}