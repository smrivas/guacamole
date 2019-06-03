<?php
/**
 * <strong>Name :  CollectionIterator.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Collection
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Result\Collection;


class CollectionIterator implements \Iterator
{

    protected $collection = null;
    protected $position = 0;
    /**
     * CollectionIterator constructor.
     */
    public function __construct(CollectionInterface $collection)
    {
        $this->collection = $collection;
    }

    public function current()
    {
        return $this->collection[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->collection[$this->position];
    }

    public function valid()
    {
        return isset($this->collection[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}