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


use Core\Filter\Join\JoinTable\JoinTableInterface;

abstract class AbstractJoin implements JoinInterface
{
    /** @var null|JoinTableInterface  */
    protected $joinTable = null;
    /** @var null|string  */
    protected $baseAlias = null;
    /** @var null|string  */
    protected $baseTable = null;

    protected $entity1;
    protected $field1;
    protected $entity2;
    protected $field2;
}