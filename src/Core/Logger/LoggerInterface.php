<?php
/**
 * <strong>Name :  LoggerInterface.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Logger
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Logger;


interface LoggerInterface
{
    /**
     * log
     * @param $data
     * @param int $level
     * @return mixed
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function log($data, $level = 0);

}