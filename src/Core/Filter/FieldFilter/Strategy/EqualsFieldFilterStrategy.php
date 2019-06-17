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


use Core\Entity\EntityDependencyInterface;
use Core\Entity\EntityInterface;
use Core\Filter\FieldFilter\FieldFilterInterface;

class EqualsFieldFilterStrategy extends AbstractFieldFilterStrategy
{
    /**
     * transform
     * @param string $entity
     * @param FieldFilterInterface $filter
     * @return array
     * @throws \EntityConfigurationFieldNotExistException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function transform(string $entity, FieldFilterInterface $filter) : array
    {
        /** @var EntityInterface $entity */
        $this->entity = $entity;

        $alias = $filter->getFieldAlias();

        if (empty($alias)) {
            $alias = $entity::getModelConfig()["table"];
        }
        $transformation = [$alias.".".$this->mapField($filter) => $filter->getValue()];

        return $transformation;
    }

}