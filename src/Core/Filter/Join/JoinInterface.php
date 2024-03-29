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


use Core\Filter\Join\JoinTable\JoinTableInterface;

interface JoinInterface
{
    /**
     * getJoinTable
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getJoinTable() : JoinTableInterface;

    /**
     * generateJoinTable
     * @return string|array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function generateJoinTable();

    /**
     * getJoinExpresion
     * @return string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getJoinExpresion() : string;

    /**
     * setBaseTable
     * @param string $baseTable
     * @param string $baseAlias
     * @return JoinInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function setBaseTable(string $baseTable = '',string $baseAlias = '') : JoinInterface;

    /**
     * getBaseTable
     * @return null|string
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getBaseTable() : ?string;

    /**
     * joinWith
     * @param string $fromEntity
     * @param string $entityJoin
     * @param string $alias
     * @param array $customFieldsFetch
     * @return JoinInterface
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function joinWith(string $fromEntity, string $entityJoin, string $alias = '', string $fromAlias = '', array $customFieldsFetch = []): JoinInterface;

    /**
     * getColumns
     * @return array
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function getColumns() : array;

    public function __toString() : string;
}