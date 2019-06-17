<?php
/**
 * <strong>Name :  EntityDependencyInterface.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Entity
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Entity;


interface EntityDependencyInterface
{
    static public function extractDependencyDetails($name): array;
}