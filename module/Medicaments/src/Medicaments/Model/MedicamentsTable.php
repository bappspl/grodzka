<?php
namespace Medicaments\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MedicamentsTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }

            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function save(Medicaments $medicaments)
    {
        $data = array(
            'name' => $medicaments->getName(),
            'description' => $medicaments->getDescription()
        );

        $id = (int) $medicaments->getId();
        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else
        {
            if ($this->getOneBy(array('id' => $id)))
            {
                $this->tableGateway->update($data, array('id' => $id));
            } else
            {
                throw new \Exception('Medicaments id does not exist');
            }
        }

        return $id;
    }

    public function deleteMedicaments($ids)
    {
        if(!is_array($ids))
        {
            $ids = array($ids);
        }

        foreach($ids as $id)
        {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}