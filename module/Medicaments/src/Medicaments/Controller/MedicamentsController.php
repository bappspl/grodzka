<?php
namespace Medicaments\Controller;

use CmsIr\System\Util\Inflector;
use Medicaments\Form\MedicamentsForm;
use Medicaments\Form\MedicamentsFormFilter;
use Medicaments\Model\Medicaments;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class MedicamentsController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'id');

            $listData = $this->getMedicamentsTable()->getDatatables($columns, $data);

            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
                "aaData" => $listData['aaData']
            );

            $jsonObject = Json::encode($output, true);
            echo $jsonObject;
            return $this->response;
        }

        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createAction()
    {
        $form = new MedicamentsForm();

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new MedicamentsFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $medicaments = new Medicaments();
                $medicaments->exchangeArray($form->getData());

                $id = $this->getMedicamentsTable()->save($medicaments);

                $this->flashMessenger()->addMessage('Informacja o lekach została dodana poprawnie.');
                return $this->redirect()->toRoute('medicaments');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('medicaments_id');

        /* @var $medicaments Medicaments */
        $medicaments = $this->getMedicamentsTable()->getOneBy(array('id' => $id));

        if(!$medicaments)
        {
            return $this->redirect()->toRoute('medicaments');
        }

        $form = new MedicamentsForm();

        $form->bind($medicaments);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter(new MedicamentsFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $id = $this->getMedicamentsTable()->save($medicaments);

                $this->flashMessenger()->addMessage('Informacja o lekach została edytowana poprawnie.');
                return $this->redirect()->toRoute('medicaments');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('medicaments_id', 0);

        if (!$id)
        {
            return $this->redirect()->toRoute('medicaments');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak')
            {
                $id = $request->getPost('id');

                if(!is_array($id))
                {
                    $id = array($id);
                }

                $this->getMedicamentsTable()->deleteMedicaments($id);
                $this->flashMessenger()->addMessage('Element został usunięty poprawnie.');

                $modal = $request->getPost('modal', false);
                if($modal == true)
                {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('file');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getMedicamentsTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \Medicaments\Model\MedicamentsTable
     */
    public function getMedicamentsTable()
    {
        return $this->getServiceLocator()->get('Medicaments\Model\MedicamentsTable');
    }
}