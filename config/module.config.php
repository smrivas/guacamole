<?php
/**
 * <strong>Name :  module.config.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */
return [
    "service_manager" => [
        "invokable" => [

        ],
        "factories" => [
            "Core\AdapterFactory" => \Core\Adapter\AdapterFactory\AdapterFactoryFactory::class,
            "Core\BaseCacheAdapter" => \Core\Cache\CacheFactory::class,
            "Core\MemcachedAdapter" => \Core\Cache\Adapters\MemcachedAdapterFactory::class,
            "Core\CacheAPCAdapter" => \Core\Cache\Adapters\APCAdapterFactory::class
        ]
    ]

];