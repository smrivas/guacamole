<?php
/**
 * <strong>Name :  BaseJoin.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\Join
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\Join;


use Core\Entity\EntityDependencyInterface;
use Core\EntityConfiguration\EntityConfiguration;
use Core\Filter\Join\JoinTable\JoinTable;
use Core\Filter\Join\JoinTable\JoinTableInterface;

class BaseJoin extends AbstractJoin
{

    /**
     * @inheritDoc
     */
    public function getJoinTable(): JoinTableInterface
    {
        return $this->joinTable;
    }

    /**
     * @inheritDoc
     */
    public function generateJoinTable()
    {
        return $this->joinTable->getTableString();
    }

    /**
     * @inheritDoc
     */
    public function setBaseTable(string $baseTable = ''): JoinInterface
    {
        $this->baseTable = $baseTable;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getBaseTable(): ?string
    {
        return $this->baseTable;
    }

    /**
     * getJoinExpresion
     * @return string
     * @throws \DependencyDetailsException
     * @throws \EntityConfigurationFieldNotExistException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getJoinExpresion(): string
    {
        /** @var EntityDependencyInterface $baseTable */
        $baseTable = $this->baseTable;

        $joinTable = explode("\\", $this->joinTable->getTable());
        $joinTable = $joinTable[count($joinTable) - 1];
        $joinDetails = $baseTable::extractDependencyDetails($joinTable);

        if (empty($joinDetails)) {
            throw new \DependencyDetailsException();
        }

        $joinValue = EntityConfiguration::mapField($baseTable, $joinDetails["joinValue"]);
        $joinField = EntityConfiguration::mapField($this->joinTable->getTable(), $joinDetails["joinField"], true);

        $joinTableName = $this->joinTable->getTableString();
        if (is_array($joinTableName)) {
            $joinTableName = key($joinTableName);
        }


        $joinExpression = $this->getBaseTableString() . "." . $joinValue . "=" . $joinTableName . "." . $joinField;

        return $joinExpression;
    }

    /**
     * getBaseTableString
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getBaseTableString(): string
    {
        $config = $this->baseTable::getModelConfig();
        return $config["table"];
    }

    public function joinWith(string $entityJoin, string $alias = '', array $customFieldsFetch = []): JoinInterface
    {
        $this->joinTable = new JoinTable($entityJoin, $customFieldsFetch, $alias);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->baseTable . ":" . $this->joinTable;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return $this->joinTable->getColumns();
    }


}