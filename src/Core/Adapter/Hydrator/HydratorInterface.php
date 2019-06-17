<?php
/**
 * <strong>Name :  ResultHydrator.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Hydrator
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Hydrator;


use Core\Adapter\Result\SQLResultInterface;
use Core\Entity\EntityInterface;

interface HydratorInterface
{
    /**
     * hydrate
     * @param string $entity
     * @param $result
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function hydrate(string $entity, $result);

    public function extract($source, $strategy);


    public function addStrategy($name, $strategy) : HydratorInterface;
}