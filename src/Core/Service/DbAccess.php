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
    public function addAdapter(AdapterInterface $adapter);
    public function getAdapters() : array;
}