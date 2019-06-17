<?php
/**
 * <strong>Name :  CacheInterface.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Cache
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Cache;


use Core\Cache\Adapters\AdapterInterface;

interface CacheInterface
{

    public function generateHash() : string;

    public function get(string $hash);

    public function has(string $hash);

    public function set(string $hash, $obj) : bool;

    public function setFallBack(AdapterInterface $fallBack) : CacheInterface;
}