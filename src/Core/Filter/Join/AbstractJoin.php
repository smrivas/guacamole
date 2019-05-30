<?php
/**
 * <strong>Name :  AbstractJoin.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\Join
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\Join;


abstract class AbstractJoin implements Join
{
    protected $entity1;
    protected $field1;
    protected $entity2;
    protected $field2;
}