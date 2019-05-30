<?php
/**
 * <strong>Name :  EqualsFieldFilter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\FieldFilter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\FieldFilter;


class EqualsFieldFilter extends AbstractFieldFilter
{

    protected $field;
    protected $value;
    protected $conditionFn = "=";

    /**
     * EqualsFieldFilter constructor.
     */
    public function __construct(array $condition = [])
    {

        $this->field = key($condition);
        $this->value = reset($condition);
    }

    public function getExpresion(): array
    {
        return [$this->field => $this->value];
    }


}