<?php
/**
 * <strong>Name :  JoinTableInterface.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\Join\JoinTable
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\Join\JoinTable;


interface JoinTableInterface
{
    public function getTable() : string;
    public function getTableString() : string;
    public function getColumns() : array;

    public function __toString() : string;

}