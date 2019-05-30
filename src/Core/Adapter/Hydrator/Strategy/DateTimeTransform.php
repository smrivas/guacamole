<?php
/**
 * <strong>Name :  DateTimeTransform.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Transformation
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Hydrator\Strategy;


class DateTimeTransform implements StrategyInterface
{
    public function hydrate($value)
    {
        if(is_string($value)) {
            return new \DateTime($value);
        }
        return null;
    }

    public function extract($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format("Y-m-d h:i:s");
        }
        return null;
    }
}