<?php
/**
 * <strong>Name :  AdapterInterface.php</strong></br>
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
 * Interface AdapterInterface
 * @package Core\Cache\Adapters
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 */
interface AdapterInterface
{
    /**
     * Checks in the configured adapter if exists an entry with the key $key
     * @param string $key
     * @return bool
     */
    public function hasItem(string $key);

    /**
     * Returns the required item by key from the configured adapter
     * @api
     * @access public
     * @param string $key
     * @return mixed
     */
    public function getItem(string $key);

    /**
     * Stores the item $val with the key $key in the configured adapter.
     * Stores the result of the operation in the success argument.
     * @param string $key
     * @param $val
     * @param bool $success
     * @return bool|mixed
     */
    public function setItem(string $key, $value, &$success = false);


    /**
     * Clears the cache adapter
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function clear();
}
