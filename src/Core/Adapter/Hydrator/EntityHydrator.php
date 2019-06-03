<?php
/**
 * <strong>Name :  EntityHydrator.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Hydrator
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Hydrator;


use Core\Adapter\AdapterFactory\AdapterFactoryInterface;
use Core\Adapter\Error\HydratorClassNotExistsException;
use Core\Adapter\Hydrator\Strategy\StrategyInterface;
use Core\Entity\BaseEntity;
use Core\Entity\EntityInterface;

class EntityHydrator extends BaseHydrator
{

    /**
     * hydrate
     * @param string $entity
     * @param array $result
     * @return BaseEntity
     * @throws HydratorClassNotExistsException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function hydrate(string $entity, $result): BaseEntity
    {

        if (class_exists($entity)) {
            /** @var BaseEntity $obj */
            $obj = new $entity;
            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    $method = 'set' . ucfirst($key);
                    if (method_exists($obj, $method)) {
                        $obj->$method($this->hydrateValue($key, $value));
                    }
                }
            }
            $obj->setAdapterFactory($this->adapterFactory);

            return $obj;
        } else {
            throw new HydratorClassNotExistsException();
        }
    }


    protected function hydrateValue($key, $value)
    {
        if (!empty($this->strategies[$key])) {
            $strategy = $this->strategies[$key];
            if (class_exists($strategy)) {
                /** @var StrategyInterface $strategyObj */
                $strategyObj = new $strategy;
                return $strategyObj->hydrate($value);
            }
        }
        return $value;
    }

    /**
     * extract
     * @param EntityInterface $source
     * @param $strategy
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function extract($source, $mapping = [])
    {
        $extractedData = [];

        $fieldMapping = $source->getFields();
        if (!empty($mapping)) {
            $fieldMapping = $mapping;
        }

        foreach ($fieldMapping as $field => $columnName) {
            $getter = "get" . ucwords($field);
            if (is_callable([$source, $getter])) {
                $value = call_user_func([$source, $getter]);

                $name = $columnName;
                if (is_array($columnName)) {
                    $name = $columnName["fieldName"];

                    if (!empty($columnName["transform"])) {
                        $transform = $columnName["transform"];
                        if (class_exists($transform)) {
                            /** @var StrategyInterface $transformObj */
                            $transformObj = new $transform;
                            $value = $transformObj->extract($value);
                        }
                    }
                }

                $extractedData[$name] = $value;
            }
        }

        return $extractedData;
    }

}