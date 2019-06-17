<?php
/**
 * <strong>Name :  AdapterFactoryInterface.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\AdapterFactory
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\AdapterFactory;


use Core\Adapter\AdapterInterface;
use Core\Entity\EntityInterface;

interface AdapterFactoryInterface
{
    public function build(string $entity) : AdapterInterface;
}