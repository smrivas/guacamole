<?php
/**
 * <strong>Name :  DbAccess.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Service
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Service;


use Core\Adapter\AdapterInterface;

interface DbAccess
{
    /**
     * addAdapter
     * @param AdapterInterface $adapter
     * @param string $name
     * @return ServiceInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function addAdapter(AdapterInterface $adapter, string $name = '') : ServiceInterface;

    /**
     * getAdapters
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getAdapters() : array;

    /**
     * getAdapter
     * @param string $name
     * @return AdapterInterface|null
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getAdapter(string $name) : ?AdapterInterface;
}