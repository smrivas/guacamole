<?php
/**
 * <strong>Name :  AbstractSelectResult.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Result;


class BaseSelectResult implements SQLResultInterface
{

    protected $data = [];

    /**
     * AbstractPersistResult constructor.
     */
    public function __construct($resultSet)
    {
        if (is_array($resultSet)) {
            $this->data = $resultSet;
        }
    }

    public function getFirst(): array
    {
        return reset($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }


}