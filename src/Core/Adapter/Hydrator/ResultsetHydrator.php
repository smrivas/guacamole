<?php
/**
 * <strong>Name :  ResultsetHydrator.php</strong></br>
 * <strong>Desc :  [Put a description here]</strong></br>
 *
 * @category rdsdb-api
 * @package Core\Adapter\Result\Hydrator
 * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
 * @copyright 2019 digimobil.es
 * @link https://gq-api.digimobil.es
 */


namespace Core\Adapter\Hydrator;


use Core\Adapter\Error\HydratorClassNotExistsException;
use Core\Adapter\Error\HydratorResultSetException;
use Core\Adapter\Result\BaseSelectResult;
use Core\Adapter\Result\SQLResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ResultsetHydrator extends BaseHydrator
{
    /**
     * hydrate
     * @param string $entity
     * @param $result
     * @return SQLResultInterface|null
     * @throws HydratorClassNotExistsException
     * @throws HydratorResultSetException
     * @author Juan Pablo Cruz Maseda <pablo.cruz@digimobil.es>
     */
    public function hydrate(string $entity, $result) : ?SQLResultInterface
    {
        if (class_exists($entity)) {
            if ($result instanceof ResultSet) {

                $resultData = $result->toArray();
                return new BaseSelectResult($resultData);

            } else {
                throw new HydratorResultSetException();
            }
        } else {
            throw new HydratorClassNotExistsException();
        }
    }

    public function extract($source, $strategy)
    {
        // TODO: Implement extract() method.
    }



}