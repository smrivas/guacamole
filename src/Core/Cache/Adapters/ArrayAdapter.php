<?php
/**
 * <strong>Name :  ArrayAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category Api
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */

namespace Core\Cache\Adapters;

use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\StorageFactory;

/**
 * Class ArrayAdapter
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 */
class ArrayAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var
     */
    protected static $memory;
    /** @var StorageInterface $arrayCache */
    static protected $cache;

    /**
     * ArrayAdapter constructor.
     * @param $ttl
     */
    public function __construct($ttl = 300)
    {
        if (null === self::$cache) {
            self::$cache = StorageFactory::factory([
                'adapter' => [
                    'name' => 'memory',
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
        return self::$cache->getItem($key);
    }

    /**
     * @inheritDoc
     */
    public function setItem(string $key, $val, &$success = false)
    {
        return self::$cache->setItem($key, $val, $success);
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
