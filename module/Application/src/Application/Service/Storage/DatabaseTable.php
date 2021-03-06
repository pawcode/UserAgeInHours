<?php
/**
 * The database table storage mechanism for retrieving entries.
 *
 * @category   Application
 * @package    Application\Service\Storage
 * @class      DatabaseTable
 * @copyright  Copyright (c) 2015
 * @author     Ashley Bickerstaff
 */

namespace Application\Service\Storage;

use Application\Entity\Entry;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class DatabaseTable implements StorageInterface
{

    /**
     * Table gateway instance.
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * Construct the class using the TableGateway object.
     * @param TableGateway $entryTableGateway
     */
    public function __construct(TableGateway $entryTableGateway)
    {
        $this->setTableGateway($entryTableGateway);
    }

    /**
     * @param TableGateway $tableGateway
     * @return $this
     */
    public function setTableGateway(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }

    /**
     * @return TableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * Return an array of entities from storage.
     * @return mixed
     */
    public function getAll()
    {
        $results = $this->getTableGateway()->select(function(Select $select) {
            $select->order('timestamp DESC');
        });
        return $results;
    }

    /**
     * Persist an entity in storage.
     * @param $entity
     * @return mixed
     */
    public function create($entity)
    {
        $affectedRows =  $this->getTableGateway()->insert(array(
            Entry::FIELD_NAME => $entity->getName(),
            Entry::FIELD_DOB => $entity->getDateOfBirth()->format("Y-m-d H:i:s")
        ));
        return (int) $this->getTableGateway()->getLastInsertValue();
    }
}