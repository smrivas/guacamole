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


namespace Core\Adapter\EntityConfiguration;


use EntityConfigurationFieldNotExistException;

class EntityConfiguration
{
    protected $fieldMapping = [];
    protected $table = "";
    protected $dependencies = [];

    public function setEntity(string $entity)
    {
        if (class_exists($entity)) {
            $entityConfig = $entity::getModelConfig();

            $this->table = $entityConfig["table"];
            $this->fieldMapping = $entityConfig["fieldMapping"];
            $this->dependencies = $entityConfig["dependencies"];
        }
    }


    /**
     * @return mixed
     */
    public function getFieldMapping()
    {
        return $this->fieldMapping;
    }

    /**
     * @param mixed $fieldMapping
     * @return EntityConfiguration
     */
    public function setFieldMapping($fieldMapping)
    {
        $this->fieldMapping = $fieldMapping;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     * @return EntityConfiguration
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @param mixed $dependencies
     * @return EntityConfiguration
     */
    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;
        return $this;
    }

    public function getPrimaryKeyField()
    {
        $primaryKeysFiltered = array_filter($this->fieldMapping, function ($element) {
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
     * @param $field
     * @return string
     * @throws EntityConfigurationFieldNotExistException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function mapField($field) : string
    {
        $fieldMapping = $this->getFieldMapping();

        if (empty($fieldMapping[$field])) {
            throw new EntityConfigurationFieldNotExistException();
        }
        if (is_array($fieldMapping[$field])) {
            return $fieldMapping[$field]["fieldName"];
        } else {
            return $fieldMapping[$field];
        }
    }
}