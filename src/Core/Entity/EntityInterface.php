<?php
/**
 * <strong>Name :  Entity.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */

namespace Core\Entity;

interface EntityInterface
{
    static public function getModelConfig(): array;

    public function getId();

    public function exchangeArray(array $data = []);

    public function getFields(): array;
}