<?php
/**
 * <strong>Name :  CollectionInterface.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Collection
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Collection;


interface CollectionInterface
{
    public function add($element, $key = null) : CollectionInterface;
    public function toArray() : array;
    public function first();
}