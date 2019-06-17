<?php
/**
 * <strong>Name :  AbstractCollection.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Collection
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Collection;

abstract class AbstractCollection implements CollectionInterface, \ArrayAccess, \IteratorAggregate, \Serializable
{
    /** @var array */
    protected $data = [];


    /**
     * AbstractCollection constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }


    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function add($element, $key = null): CollectionInterface
    {

        if ($key) {
            $this->data[$key] = $element;
        } else {
            $this->data[] = $element;
        }
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }

    public function first()
    {
        return reset($this->data);
    }


}