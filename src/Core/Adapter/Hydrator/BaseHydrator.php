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


abstract class BaseHydrator implements HydratorInterface
{
    protected $strategies = [];

    public function addStrategy($name, $strategy) : HydratorInterface
    {
        $this->strategies[$name] = $strategy;
        return $this;
    }
}