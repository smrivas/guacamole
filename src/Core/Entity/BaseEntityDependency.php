<?php
/**
 * <strong>Name :  BaseEntityDependency.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Entity
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Entity;


use Core\Adapter\AdapterFactory\AdapterFactoryInterface;
use Core\Entity\Error\DependencyConfigException;
use Core\Entity\Error\EntityMethodNotExistsException;

abstract class BaseEntityDependency implements EntityDependencyInterface, EntityInterface
{

    /** @var AdapterFactoryInterface|null */
    protected $adapterFactory = null;

    protected $dependenciesResolved = [];

    /**
     * @return AdapterFactoryInterface|null
     */
    public function getAdapterFactory(): ?AdapterFactoryInterface
    {
        return $this->adapterFactory;
    }

    /**
     * @param AdapterFactoryInterface|null $adapterFactory
     * @return BaseEntityDependency
     */
    public function setAdapterFactory(?AdapterFactoryInterface $adapterFactory): BaseEntityDependency
    {
        $this->adapterFactory = $adapterFactory;
        return $this;
    }

    public function __debugInfo() {
        $fields = get_object_vars($this);
        unset ($fields["adapterFactory"]);
        unset ($fields["dependenciesResolved"]);
        return $fields;
    }


    /**
     * resolveDependency
     * @param $name
     * @return EntityInterface|null
     * @throws DependencyConfigException
     * @throws EntityMethodNotExistsException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    protected function resolveDependency($name): ?EntityInterface
    {

        if (!isset($this->dependenciesResolved[$name])) {
            if (!empty($this->collection)) {
                $this->collection->resolveDependency($name);
                $dependency = $this->{lcfirst($name)};
            } else {
                $dependencyConfig = $this->extractDependencyDetails($name);

                if (empty($dependencyConfig["joinField"])
                    || empty($dependencyConfig["joinValue"])
                    || empty($dependencyConfig["entity"])) {
                    throw new DependencyConfigException();
                }
                $entityName = $dependencyConfig["entity"];
                $entityAdapter = $this->adapterFactory->build($entityName);


                $joinField = $dependencyConfig["joinField"];
                $joinValueName = "get" . ucwords($dependencyConfig["joinValue"]);

                if (!method_exists($this, $joinValueName)) {
                    throw new EntityMethodNotExistsException();
                }

                $joinValue = call_user_func([$this, $joinValueName]);

                $dependency = $entityAdapter->findBy($entityName, $joinField, $joinValue);

                $this->{lcfirst($name)} = $dependency;
                $this->dependenciesResolved[$name] = $dependency;
            }
            return $dependency;
        } else {
            return $this->dependenciesResolved[$name];
        }
    }

    public function injectDependency($name, $dependency)
    {
        $this->{lcfirst($name)} = $dependency;
        $this->dependenciesResolved[$name] = $dependency;
    }

    public function extractDependencyDetails($name): array
    {
        $config = static::getModelConfig();

        if (!empty($config["dependencies"]) && !empty($config["dependencies"][$name])) {
            return $config["dependencies"][$name];
        }
        return [];
    }
}