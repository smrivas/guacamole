<?php
/**
 * <strong>Name :  APCAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category Api
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */

namespace Core\Cache\Adapters;

use Zend\Cache\StorageFactory;
use Zend\Session\Storage\StorageInterface;


class APCAdapter extends AbstractAdapter
{
    /**
     * @inheritDoc
     */
    static public $misses;
    /**
     * @inheritDoc
     */
    static public $hits;
    /** @var \Zend\Cache\Storage\StorageInterface $cache */
    static protected $cache;


    public function __construct($ttl = 3600 * 10 * 10)
    {
        if (null === self::$cache) {
            self::$cache = StorageFactory::factory([
                'adapter' => [
                    'name' => 'apcu',
                    'options' => ['ttl' => $ttl],
                ],
                'plugins' => [
                    'exception_handler' => ['throw_exceptions' => true],
                ]
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getItem(string $key)
    {
        $value = self::$cache->getItem($key);
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function setItem(string $key, $val, &$success = false)
    {
        $result = self::$cache->setItem($key, $val, $success);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function hasItem(string $key)
    {
        return self::$cache->hasItem($key);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        self::$cache->flush();
    }
}
