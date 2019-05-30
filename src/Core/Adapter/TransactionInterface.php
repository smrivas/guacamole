<?php
/**
 * <strong>Name :  Transaction.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter;


use Core\Adapter\Error\TransactionError;

interface TransactionInterface
{
    /**
     * startTransaction
     * @return AdapterInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function startTransaction(): AdapterInterface;


    /**
     * inTransaction
     * @return bool
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function inTransaction(): bool;

    /**
     * commit
     * @return mixed
     * @throws TransactionError
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function commit();

    /**
     * rollback
     * @return void
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function rollback(): void;
}