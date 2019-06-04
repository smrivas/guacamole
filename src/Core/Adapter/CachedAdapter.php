<?php
/**
 * <strong>Name :  CachedAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter;


use Core\Cache\CacheInterface;

abstract class CachedAdapter
{
    /** @var CacheInterface */
    protected $cache;

    /**
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }


    /**
     * @param CacheInterface $cache
     * @return CachedAdapter
     */
    public function setCache(CacheInterface $cacheStrategy = null)
    {
        if ($cacheStrategy) {
            $this->cache = $cacheStrategy;
        }
        return $this;
    }


}