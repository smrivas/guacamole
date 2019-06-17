<?php
/**
 * <strong>Name :  UniqueIdentifierTransform.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Hydrator\Strategy
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Field\Strategy;


class UniqueIdentifierTransform implements FieldStrategy
{
    public function transform($name)
    {
        return new \Zend\Db\Sql\Expression('CAST(' . $name . ' as varchar(36))');
    }
}