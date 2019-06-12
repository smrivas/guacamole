<?php
/**
 * <strong>Name :  AbstractLogger.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Logger
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Logger;


abstract class AbstractLogger implements LoggerInterface
{
    /**
     * @inheritDoc
     */
    abstract public function log($data, $level);
}