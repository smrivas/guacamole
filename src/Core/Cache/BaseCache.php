<?php
/**
 * <strong>Name :  AbstractCache.php</strong></br>
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
use Core\Cache\Adapters\APCAdapter;

class BaseCache implements CacheInterface
{
    protected $_inMemoryCache = [];

    /** @var null|AdapterInterface */
    protected $adapter = null;
    /** @var null|AdapterInterface  */
    protected $fallBack = null;

    /**
     * BaseCache constructor.
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;
    }


    public function generateHash(): string
    {
        $functionDetails = $this->getLastFunctionDetails();

        /** @var string $fn_name */
        /** @var string $fn_class */
        /** @var array $fn_args */
        extract($functionDetails, EXTR_PREFIX_ALL, "fn");

        $hash = $fn_class . ":" . $fn_name . ":".array_reduce($fn_args, function($carry = '', $item) {
            $carry .= $item;
            return $carry;
        });

        return ($hash);

    }

    protected function getLastFunctionDetails()
    {
        $backtrace = debug_backtrace();
        $fnName = $fnClass = $fnArgs = null;
        if (count($backtrace) >= 3) {
            $fnDetailsRaw = $backtrace[2];
            $fnName = $fnDetailsRaw["function"];
            $fnClass = $fnDetailsRaw["class"];
            $fnArgs = $fnDetailsRaw["args"];
        }

        return ["name" => $fnName, "class" => $fnClass, "args" => $fnArgs];
    }

    public function get(string $hash)
    {
        $obj = $this->adapter->getItem($hash);
        return $obj;
    }

    public function has(string $hash)
    {
        if ($this->adapter->hasItem($hash)) {
            return true;
        } else if ($this->fallBack->hasItem($hash)) {
            $this->adapter->setItem($hash, $this->fallBack->getItem($hash));
            return true;
        }
        return false;
    }

    public function set(string $hash, $obj): bool
    {
        $success = false;
        $this->adapter->setItem($hash, $obj, $success);
        $this->fallBack->setItem($hash, $obj, $success);
        return $success;
    }

    public function setFallBack(AdapterInterface $fallBack) : CacheInterface
    {
        $this->fallBack = $fallBack;
        return $this;
    }
}