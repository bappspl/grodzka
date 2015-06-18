<?php
namespace Pharmacy\Controller;

use CmsIr\System\Util\Inflector;
use Pharmacy\Form\PharmacyForm;
use Pharmacy\Form\PharmacyFormFilter;
use Pharmacy\Model\Pharmacy;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class PharmacyController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'id');

            $listData = $this->getPharmacyTable()->getDatatables($columns, $data);

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
        $form = new PharmacyForm();

        $request = $this->getRequest();

        $places = $this->getPlaceService()->findAsAssocArray();

        $form->get('place_id')->setValueOptions($places);

        if ($request->isPost())
        {
            $form->setInputFilter(new PharmacyFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $pharmacy = new Pharmacy();
                $pharmacy->exchangeArray($form->getData());

                $id = $this->getPharmacyTable()->save($pharmacy);

                $this->flashMessenger()->addMessage('Apteka została dodana poprawnie.');
                return $this->redirect()->toRoute('pharmacy');
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
        $id = $this->params()->fromRoute('pharmacy_id');

        /* @var $pharmacy Pharmacy */
        $pharmacy = $this->getPharmacyTable()->getOneBy(array('id' => $id));

        if(!$pharmacy)
        {
            return $this->redirect()->toRoute('pharmacy');
        }

        $form = new PharmacyForm();

        $places = $this->getPlaceService()->findAsAssocArray();

        $form->get('place_id')->setValueOptions($places);

        $form->bind($pharmacy);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter(new PharmacyFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $id = $this->getPharmacyTable()->save($pharmacy);

                $this->flashMessenger()->addMessage('Apteka została edytowana poprawnie.');
                return $this->redirect()->toRoute('pharmacy');
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
        $id = (int) $this->params()->fromRoute('pharmacy_id', 0);

        if (!$id)
        {
            return $this->redirect()->toRoute('pharmacy');
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

                $this->getPharmacyTable()->deletePharmacy($id);
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
            'page'  => $this->getPharmacyTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \Pharmacy\Model\PharmacyTable
     */
    public function getPharmacyTable()
    {
        return $this->getServiceLocator()->get('Pharmacy\Model\PharmacyTable');
    }

    /**
    * @return \CmsIr\Place\Service\PlaceService
    */
    public function getPlaceService()
    {
        return $this->getServiceLocator()->get('CmsIr\Place\Service\PlaceService');
    }
}