<?php
/**
 * <strong>Name :  EntityCollection.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Collection
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Collection;


use Core\Adapter\AdapterFactory\AdapterFactoryInterface;
use Core\Entity\BaseEntity;
use Core\Entity\EntityInterface;
use Core\Entity\Error\DependencyConfigException;
use Core\Filter\BaseFilter;
use Core\Filter\FieldFilter\EqualsFieldFilter;

class EntityCollection extends AbstractCollection
{
    /** @var null|AdapterFactoryInterface */
    protected $adapterFactory = null;
    /**
     * resolveDependency
     * @param $name
     * @throws DependencyConfigException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function resolveDependency($name)
    {
        $ids = [];
        /** @var EntityInterface $entity */
        foreach ($this->data as $entity) {
            $ids[] = $entity->getId();
        }

        /** @var BaseEntity $firstEntity */
        $firstEntity = $this->data[0];

        $dependencyConfig = $firstEntity::extractDependencyDetails($name);

        if (empty($dependencyConfig["joinField"])
            || empty($dependencyConfig["joinValue"])
            || empty($dependencyConfig["entity"])) {
            throw new DependencyConfigException();
        }

        $entityName = $dependencyConfig["entity"];
        $entityAdapter = $this->adapterFactory->build($entityName);

        $joinField = $dependencyConfig["joinField"];

        $filter = new BaseFilter();
        $filter->setEntity($entityName);

        $equalFilter = new EqualsFieldFilter($entityName, [$joinField => $ids]);
        $filter->addFilter($equalFilter);

        $dependencies = $entityAdapter->search($filter);
        
        foreach ($dependencies as $dependency) {
            $joinValueName = "get" . ucwords($dependencyConfig["joinField"]);
            $id = call_user_func([$dependency, $joinValueName]);
            /** @var BaseEntity $entity */
            foreach ($this->data as $entity ) {
                if ($entity->getId() === $id) {
                    $entity->injectDependency($name,$dependency);
                    break;
                }
            }
        }
    }


    /**
     * @return AdapterFactoryInterface|null
     */
    public function getAdapterFactory(): ?AdapterFactoryInterface
    {
        return $this->adapterFactory;
    }

    /**
     * @param AdapterFactoryInterface|null $adapterFactory
     * @return AbstractCollection
     */
    public function setAdapterFactory(?AdapterFactoryInterface $adapterFactory): AbstractCollection
    {
        $this->adapterFactory = $adapterFactory;
        return $this;
    }

}