<?php
/**
 * <strong>Name :  EntityConfiguration.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\EntityConfiguration
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\EntityConfiguration;


use EntityConfigurationFieldNotExistException;

class EntityConfiguration implements EntityConfigurationInterface
{
    /** @var Configuration[] */
    protected static $data;

    /**
     * getDependencies
     * @param string $entity
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static public function getDependencies(string $entity): array
    {
        if (!isset(self::$data[$entity])) {
            self::setEntity($entity);
        }
        return self::$data[$entity]->getDependencies();
    }

    /**
     * setEntity
     * @param string $entity
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static protected function setEntity(string $entity)
    {
        if (!isset(self::$data[$entity]) && class_exists($entity)) {
            $entityConfig = $entity::getModelConfig();

            $configuration = new Configuration();
            $configuration->setTable($entityConfig["table"]);
            $configuration->setFieldMapping($entityConfig["fieldMapping"]);
            $configuration->setDependencies($entityConfig["dependencies"]);

            self::$data[$entity] = $configuration;
        }
    }

    /**
     * hasFieldMapping
     * @param $entity
     * @param $key
     * @return bool
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static public function hasFieldMapping($entity, $key) {
        $fieldMapping = self::getFieldMapping($entity);
        return isset($fieldMapping[$key]);
    }

    /**
     * getFieldMapping
     * @param string $entity
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static public function getFieldMapping(string $entity): array
    {
        if (!isset(self::$data[$entity])) {
            self::setEntity($entity);
        }
        return self::$data[$entity]->getFieldMapping();
    }

    /**
     * getTable
     * @param string $entity
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static public function getTable(string $entity): string
    {
        if (!isset(self::$data[$entity])) {
            self::setEntity($entity);
        }
        return self::$data[$entity]->getTable();
    }

    /**
     * getPrimaryKeyField
     * @param string $entity
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static public function getPrimaryKeyField(string $entity)
    {

        if (!isset(self::$data[$entity])) {
            self::setEntity($entity);
        }
        $entityObject = self::$data[$entity];

        $primaryKeysFiltered = array_filter($entityObject->getFieldMapping(), function ($element) {
            if (is_array($element)) {
                return isset($element["isPrimaryKey"]) && $element["isPrimaryKey"];
            }
            return false;
        });

        $primaryKeys = [];
        foreach ($primaryKeysFiltered as $pk) {
            $primaryKeys[] = $pk["fieldName"];
        }

        return $primaryKeys;

    }

    /**
     * mapField
     * @param string $entity
     * @param string $field
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    static public function mapField(string $entity, string $field): string
    {
        if (isset(self::$data[$entity])) {
            $entityObject = self::$data[$entity];

            $fieldMapping = $entityObject->getFieldMapping();

            if (is_array($fieldMapping[$field])) {
                return $fieldMapping[$field]["fieldName"];
            } else {
                return $fieldMapping[$field];
            }
        }
        return $field;
    }
}