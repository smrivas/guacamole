<?php
/**
 * <strong>Name :  AbstractPersistResult.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Result;


use Zend\Db\Adapter\Driver\ResultInterface;

class BasePersistResult implements SQLResultInterface
{

    protected $result = null;

    /**
     * AbstractPersistResult constructor.
     */
    public function __construct($resultSet)
    {
        if ($resultSet instanceof ResultInterface) {
            $this->result = $resultSet->getAffectedRows();
        }
    }


    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    public function getFirst(): array
    {
        // TODO: Implement getFirst() method.
    }


}