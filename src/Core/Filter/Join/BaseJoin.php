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


use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Entity\EntityDependencyInterface;
use Core\Filter\Join\JoinTable\JoinTable;
use Core\Filter\Join\JoinTable\JoinTableInterface;
use Zend\Debug\Debug;

class BaseJoin extends AbstractJoin
{

    /**
     * @inheritDoc
     */
    public function getJoinTable() : JoinTableInterface
    {
        return $this->joinTable;
    }

    /**
     * @inheritDoc
     */
    public function generateJoinTable() : string
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

    public function getBaseTableString() : string
    {
        $config = $this->baseTable::getModelConfig();
        return $config["table"];
    }


    /**
     * @inheritDoc
     */
    public function getJoinExpresion(): string
    {
        /** @var EntityDependencyInterface $baseTable */
        $baseTable = $this->baseTable;

        $joinTable = explode("\\",$this->joinTable->getTable());
        $joinTable = $joinTable[count($joinTable) - 1];
        $joinDetails = $baseTable::extractDependencyDetails($joinTable);
        if (empty($joinDetails)) {

        }

        $baseTableEntityConfig = new EntityConfiguration();
        $baseTableEntityConfig->setEntity($baseTable);

        $joinTableEntityConfig = new EntityConfiguration();
        $joinTableEntityConfig->setEntity($this->joinTable->getTable());

        $joinValue = $baseTableEntityConfig->mapField($joinDetails["joinValue"]);
        $joinField = $joinTableEntityConfig->mapField($joinDetails["joinField"]);

        $joinExpression = $this->getBaseTableString().".".$joinValue."=".$this->joinTable->getTableString().".".$joinField;
        return $joinExpression;
    }

    public function joinWith(string $entityJoin, string $alias = '', array $customFieldsFetch = []): JoinInterface
    {
        $this->joinTable = new JoinTable($entityJoin, $customFieldsFetch, $alias);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString() : string
    {
        return $this->baseTable.":".$this->joinTable;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return $this->joinTable->getColumns();
    }


}