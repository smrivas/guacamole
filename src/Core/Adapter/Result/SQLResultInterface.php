<?php
/**
 * <strong>Name :  PersistResult.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Result;


interface SQLResultInterface
{
    /**
     * getFirst
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getFirst(): array;

    /**
     * toArray
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function toArray() : array;
}