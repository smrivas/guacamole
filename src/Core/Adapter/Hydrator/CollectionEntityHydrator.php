<?php
/**
 * <strong>Name :  CollectionEntityHydrator.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Hydrator
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Hydrator;


use Core\Adapter\Result\Collection\EntityCollection;

class CollectionEntityHydrator extends BaseHydrator
{
    protected $collectionEntity = "";

    /**
     * @return string
     */
    public function getCollectionEntity(): string
    {
        return $this->collectionEntity;
    }

    /**
     * @param string $collectionEntity
     * @return CollectionEntityHydrator
     */
    public function setCollectionEntity(string $collectionEntity): CollectionEntityHydrator
    {
        $this->collectionEntity = $collectionEntity;
        return $this;
    }


    public function hydrate(string $entity, $data) : EntityCollection
    {
        $entityHydrator = new EntityHydrator();
        $entityHydrator->setAdapterFactory($this->adapterFactory);
        $entityHydrator->setStrategy($this->getStrategy());

        if (empty($this->getCollectionEntity())) {

        }

        $collection = new EntityCollection();
        $collection->setAdapterFactory($this->adapterFactory);

        foreach ($data as $result) {
            $newEntity = $entityHydrator->hydrate($this->getCollectionEntity(), $result);
            $newEntity->setCollection($collection);
            $collection->add($newEntity);
        }

        return $collection;

    }

    public function extract($source, $strategy)
    {
        // TODO: Implement extract() method.
    }
}