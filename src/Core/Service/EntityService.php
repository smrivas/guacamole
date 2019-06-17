<?php
/**
 * <strong>Name :  EntityService.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Service
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Service;


use Core\Adapter\AdapterFactory\AdapterFactoryInterface;
use Core\Adapter\AdapterInterface;
use Core\Adapter\EntityConfiguration\EntityConfiguration;
use Core\Entity\Error\DependencyConfigException;
use Core\Filter\FilterInterface;
use Core\Filter\Join\BaseJoin;

class EntityService extends BaseService
{
    const Entity = "";

    /**
     * EntityService constructor.
     */
    public function __construct(AdapterFactoryInterface $adapterFactory)
    {
        /** @var AdapterInterface $rdsdb5General */
        $adapter = $adapterFactory->build(static::Entity);
        $this->addAdapter($adapter, static::Entity);
    }
}