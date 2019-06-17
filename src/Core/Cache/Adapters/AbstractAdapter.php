<?php
/**
 * <strong>Name :  AbstractAdapter.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category Api
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */

namespace Core\Cache\Adapters;

/**
 * Class AbstractAdapter
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 */
abstract class AbstractAdapter implements AdapterInterface
{

    /**
     * Counter for the cache misses, for performance profiling
     * @var int
     */
    protected static $misses;
    /**
     * Counter for cache hits, for performance profiling
     * @var int
     */
    protected static $hits;

    /**
     * @inheritDoc
     */
    abstract public function hasItem(string $key);

    /**
     * @inheritDoc
     */
    abstract public function getItem(string $key);

    /**
     * @inheritDoc
     */
    abstract public function setItem(string $key, $value, &$success = false);

    /**
     * @inheritDoc
     */
    abstract public function clear();
}
