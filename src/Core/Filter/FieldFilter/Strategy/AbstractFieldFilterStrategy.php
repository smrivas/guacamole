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


use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Entity\EntityInterface;
use Core\Filter\FieldFilter\FieldFilterInterface;

abstract class AbstractFieldFilterStrategy implements FieldFilterStrategyInterface
{
    /** @var string */
    protected $entity;

    abstract public function transform(string $entity,FieldFilterInterface $filter) : array;

    /**
     * mapField
     * @param FieldFilterInterface $filter
     * @return string
     * @throws \EntityConfigurationFieldNotExistException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function mapField(FieldFilterInterface $filter)
    {
        $configuration = new EntityConfiguration();
        $configuration->setEntity($this->entity);

        return $configuration->mapField($filter->getField());
    }
}