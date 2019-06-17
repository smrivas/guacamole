<?php
/**
 * <strong>Name :  BaseService.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Service
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Service;


use Core\Adapter\AdapterInterface;

class BaseService implements ServiceInterface, DbAccess
{
    /** @var AdapterInterface[] */
    protected $adapters = [];

    /**
     * @inheritdoc
     */
    public function addAdapter(AdapterInterface $adapter, string $name = '') : ServiceInterface
    {
        if (!empty($name)) {
            $this->adapters[$name] = $adapter;
        } else {
            $this->adapters[] = $adapter;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdapters(): array
    {
        return $this->adapters;
    }

    /**
     * @inheritdoc
     */
    public function getAdapter(string $name): ?AdapterInterface
    {
        return $this->adapters[$name] ?? null;
    }


}