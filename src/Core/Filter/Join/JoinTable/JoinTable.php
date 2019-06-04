<?php
/**
 * <strong>Name :  JoinTable.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\Join\JoinTable
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\Join\JoinTable;


use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Entity\EntityDependencyInterface;
use Core\Entity\EntityInterface;

class JoinTable implements JoinTableInterface
{
    protected $table;
    protected $customFields = [];
    protected $alias;

    /**
     * JoinTable constructor.
     * @param string|null $table
     * @param array $customFields
     * @param string $alias
     */
    public function __construct(string $table = null, array $customFields = [], string $alias = '')
    {
        $this->table = $table;
        $this->customFields = $customFields;
        $this->alias = $alias;
    }

    public function getTableString(): string
    {
        /** @var EntityInterface $table */
        $table = $this->table;
        $config = $table::getModelConfig();
        if (empty($config["table"])) {

        }

        $tableString = $config["table"];
        if (!empty($this->alias)) {

        }

        return $tableString;
    }

    /**
     * getColumns
     * @return array
     * @throws \EntityConfigurationFieldNotExistException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getColumns(): array
    {
        $config = new EntityConfiguration();
        $config->setEntity($this->table);

        $fields = count($this->customFields) > 0 ? $this->customFields : array_keys($config->getFieldMapping());
        $columns = [];
        foreach ($fields as $customField) {
            $fieldName = $customField;
            if (is_array($fieldName)) {
                $fieldName = $fieldName["fieldName"];
            }

            $columns[$this->getTableString().".".$fieldName] = $config->mapField($fieldName);
        }

        return $columns;
    }


    /**
     * @return mixed
     */
    public function getTable() : string
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table): void
    {
        $this->table = $table;
    }

    /**
     * @return array
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    /**
     * @param array $customFields
     */
    public function setCustomFields(array $customFields): void
    {
        $this->customFields = $customFields;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @inheritDoc
     */
    public function __toString() : string
    {
        return $this->getTable().":".json_encode($this->getCustomFields());
    }


}