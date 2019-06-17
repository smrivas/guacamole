<?php
/**
 * <strong>Name :  BaseEntity.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Entity
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Entity;

use Core\Adapter\Result\Collection\EntityCollection;

abstract class BaseEntity extends BaseEntityDependency implements EntityInterface, \Serializable
{
    protected $id;
    protected $created;
    protected $modified;
    /** @var EntityCollection|null  */
    protected $collection = null;

    abstract static public function getModelConfig(): array;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): EntityInterface
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified): EntityInterface
    {
        $this->modified = $modified;
        return $this;
    }

    public function exchangeArray(array $data = [])
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    public function getFields(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return null
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param null $collection
     * @return BaseEntity
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        $data = $this->__debugInfo();
        unset($data["collection"]);
        foreach ($data as $key => $val) {
            if ($val === null) {
                unset($data[$key]);
            }
        }
        return serialize($data);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $this->exchangeArray(unserialize($serialized));
    }

}