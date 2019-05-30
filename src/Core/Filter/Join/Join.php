<?php
/**
 * <strong>Name :  Join.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Filter\Join
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Filter\Join;


interface Join
{
    /**
     * getJoinTable
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getJoinTable() : array;

    /**
     * getJoinExpresion
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getJoinExpresion() : string;

}