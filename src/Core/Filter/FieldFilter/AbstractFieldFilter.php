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


use Core\Entity\EntityInterface;

abstract class AbstractFieldFilter implements FieldFilterInterface
{
    protected $field;
    protected $value;
    protected $fieldAlias;
    protected $conditionFn;
    protected $entity;

    /**
     * @param mixed $field
     * @return AbstractFieldFilter
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @param mixed $value
     * @return AbstractFieldFilter
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param mixed $fieldAlias
     * @return AbstractFieldFilter
     */
    public function setFieldAlias($fieldAlias)
    {
        $this->fieldAlias = $fieldAlias;
        return $this;
    }

    /**
     * @param mixed $conditionFn
     * @return AbstractFieldFilter
     */
    public function setConditionFn($conditionFn)
    {
        $this->conditionFn = $conditionFn;
        return $this;
    }

    /**
     * @param mixed $entity
     * @return AbstractFieldFilter
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }


    public function getField()
    {
        return $this->field;
    }

    public function getFieldAlias()
    {
        return $this->fieldAlias;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getConditionFn()
    {
        return $this->conditionFn;
    }

    /**
     * @return mixed
     */
    public function getEntity() : string
    {
        return $this->entity;
    }

}