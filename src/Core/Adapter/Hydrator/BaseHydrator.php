<?php
/**
 * <strong>Name :  BaseHydrator.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Hydrator
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Hydrator;


use Core\Adapter\AdapterFactory\AdapterFactoryInterface;

abstract class BaseHydrator implements HydratorInterface
{
    protected $strategies = [];

    /** @var null|AdapterFactoryInterface */
    protected $adapterFactory = null;

    /**
     * @return AdapterFactoryInterface|null
     */
    public function getAdapterFactory(): ?AdapterFactoryInterface
    {
        return $this->adapterFactory;
    }

    /**
     * @param AdapterFactoryInterface|null $adapterFactory
     * @return EntityHydrator
     */
    public function setAdapterFactory(?AdapterFactoryInterface $adapterFactory): HydratorInterface
    {
        $this->adapterFactory = $adapterFactory;
        return $this;
    }

    public function addStrategy($name, $strategy) : HydratorInterface
    {
        $this->strategies[$name] = $strategy;
        return $this;
    }

    public function setStrategy(array $strategies) : HydratorInterface
    {
        $this->strategies = $strategies;
        return $this;
    }

    public function getStrategy() : array
    {
        return $this->strategies;
    }
}