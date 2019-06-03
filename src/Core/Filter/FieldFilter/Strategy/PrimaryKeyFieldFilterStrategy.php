<?php
/**
 * <strong>Name :  PrimaryKeyFieldFilterStrategy.php</strong></br>
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

class PrimaryKeyFieldFilterStrategy extends AbstractFieldFilterStrategy
{
    public function transform(string $entity, FieldFilterInterface $filter): array
    {
        $whereConditions = [];

        $configuration = new EntityConfiguration();
        $configuration->setEntity($entity);

        $primaryKeyFields = $configuration->getPrimaryKeyField();

        $primaryKey = $filter->getValue();

        if (is_array($primaryKey)
            && is_array($primaryKeyFields)
            && count($primaryKey) === count($primaryKeyFields)
        ) {
            $whereConditions = array_combine($primaryKeyFields, $primaryKey);
        } else {
            if (count($primaryKeyFields) === 1) {
                $whereConditions[reset($primaryKeyFields)] = $primaryKey;
            }
        }

        return $whereConditions;

    }
}