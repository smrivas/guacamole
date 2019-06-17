<?php
/**
 * <strong>Name :  MemcachedAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Cache\Adapters;


class MemcachedAdapter extends AbstractAdapter
{
    /**
     * @var
     */
    protected static $memory;
    protected static $ttl;
    /** @var \CouchbaseBucket $arrayCache */
    static protected $cache;

    /**
     * MemcachedAdapter constructor.
     * @param null $cacheAdapter
     * @param int $ttl
     */
    public function __construct($cacheAdapter = null, $ttl = 3600)
    {
        if (null === self::$cache && $cacheAdapter) {
            self::$cache = $cacheAdapter;
            self::$ttl = $ttl;
        }
    }

    /**
     * @inheritDoc
     */
    public function getItem(string $key)
    {
        $data = self::$cache->get($key);
        return unserialize($data->value);
    }

    /**
     * @inheritDoc
     */
    public function setItem(string $key, $val, &$success = false)
    {
        if (empty(self::$cache->get($key))) {
            self::$cache->insert($key, serialize($val), ["expiry" => self::$ttl]);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function hasItem(string $key)
    {
        $hasItem = false;
        try {
            $response = self::$cache->get($key);
            if (!empty($response)) {
                $hasItem = true;
            }
        } catch (\Exception $e) {
        }
        return $hasItem;
    }


    /**
     * @inheritDoc
     */
    public function clear()
    {

    }


}