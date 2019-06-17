<?php
/**
 * <strong>Name :  Configuration.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\EntityConfiguration
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\EntityConfiguration;


class Configuration implements ConfigurationInterface
{
    protected $fieldMapping = [];
    protected $table = "";
    protected $dependencies = [];

    /**
     * @return mixed
     */
    public function getFieldMapping()
    {
        return $this->fieldMapping;
    }

    /**
     * @param mixed $fieldMapping
     * @return ConfigurationInterface
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
     * @return ConfigurationInterface
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
     * @return ConfigurationInterface
     */
    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;
        return $this;
    }

}