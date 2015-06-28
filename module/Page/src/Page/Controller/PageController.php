<?php

namespace Page\Controller;

use CmsIr\Page\Model\Page;
use CmsIr\Post\Model\Post;
use Medicaments\Model\MedicamentsTable;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Json\Json;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use CmsIr\Newsletter\Model\Subscriber;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class PageController extends AbstractActionController
{
    public function homeAction()
    {
        $this->layout('layout/home');

        $slider = $this->getSliderService()->findOneBySlug('slider-glowny');
        $items = $slider->getItems();

        $pharmacy = $this->getPharmacyTable()->getAll();

        $users = $this->getUsersService()->findAllWithPhotoByRoleId(2);

        $news = $this->getPostTable()->getBy(array('status_id' => 1, new IsNotNull('filename_main')), 'date DESC', 4);
        $news = array_values($news);

        foreach($news as $n)
        {
            $date = $n->getDate();
            $newDate = date('d-M-Y', strtotime($date));
            $n->setDate($newDate);
        }

        $newspapperFiles = $this->getFileTable()->getBy(array('entity_id' => 1, 'entity_type' => 'gallery'));
        $newspapperFiles = array_values($newspapperFiles);

        $medicaments = $this->getMedicamentsTable()->getAll();
        $medicaments = array_values($medicaments);

        $newspaper = $this->getFileTable()->getOneBy(array('id' => 10));

        $viewParams = array();
        $viewParams['items'] = $items;
        $viewParams['pharmacy'] = $pharmacy;
        $viewParams['users'] = $users;
        $viewParams['news'] = $news;
        $viewParams['newspapperFiles'] = $newspapperFiles;
        $viewParams['medicaments'] = $medicaments;
        $viewParams['newspaper'] = $newspaper;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewPageAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');

        $page = $this->getPageService()->findOneBySlug($slug);
        if(empty($page)){
            $this->getResponse()->setStatusCode(404);
        }

        $newspapperFiles = $this->getFileTable()->getBy(array('entity_id' => 1, 'entity_type' => 'gallery'));
        $newspapperFiles = array_values($newspapperFiles);

        $medicaments = $this->getMedicamentsTable()->getAll();
        $medicaments = array_values($medicaments);

        $users = $users = $this->getUsersService()->findAllWithPhotoByRoleId(2);
        $users = array_values($users);

        $pharmacy = $this->getPharmacyTable()->getAll();
        $pharmacy = array_values($pharmacy);

        foreach($pharmacy as $p)
        {
            $place = $this->getPlaceTable()->getOneBy(array('id' => $p->getPlaceId()));
            $p->setPlace($place);
        }

        $newspaper = $this->getFileTable()->getOneBy(array('id' => 10));

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['newspapperFiles'] = $newspapperFiles;
        $viewParams['medicaments'] = $medicaments;
        $viewParams['users'] = $users;
        $viewParams['pharmacy'] = $pharmacy;
        $viewParams['newspaper'] = $newspaper;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);

        if($slug == 'kontakt') {
            return $viewModel->setTemplate('page/page/contact.phtml');
        } elseif($slug == 'nasi-specjalisci') {
            return $viewModel->setTemplate('page/page/nasi-specjalisci.phtml');
        } else {
            return $viewModel;
        }

    }

    public function newsListAction()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $allNews = $this->getPostTable()->getBy(array('status_id' => $activeStatusId, 'category' => 'news'), 'id DESC');

        foreach($allNews as $news)
        {
            $newsId = $news->getId();
            $newsFiles = $this->getFileTable()->getBy(array('entity_id' => $newsId, 'entity_type' => 'Post'));

            $news->setFiles($newsFiles);

            $date = $news->getDate();
            $newDate = date('d-M-Y', strtotime($date));
            $news->setDate($newDate);
        }

        $newspapperFiles = $this->getFileTable()->getBy(array('entity_id' => 1, 'entity_type' => 'gallery'));
        $newspapperFiles = array_values($newspapperFiles);

        $medicaments = $this->getMedicamentsTable()->getAll();
        $medicaments = array_values($medicaments);

        $newspaper = $this->getFileTable()->getOneBy(array('id' => 10));

        $viewParams = array();
        $viewParams['news'] = $allNews;
        $viewParams['newspapperFiles'] = $newspapperFiles;
        $viewParams['medicaments'] = $medicaments;
        $viewParams['newspaper'] = $newspaper;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewNewsAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');

        /* @var $news \CmsIr\Post\Model\Post */
        $news = $this->getPostTable()->getOneBy(array('url' => $slug));
        $newsId = $news->getId();
        $newsFiles = $this->getFileTable()->getBy(array('entity_id' => $newsId, 'entity_type' => 'Post'));
        $news->setFiles($newsFiles);

        $newspapperFiles = $this->getFileTable()->getBy(array('entity_id' => 1, 'entity_type' => 'gallery'));
        $newspapperFiles = array_values($newspapperFiles);

        $medicaments = $this->getMedicamentsTable()->getAll();
        $medicaments = array_values($medicaments);

        $newspaper = $this->getFileTable()->getOneBy(array('id' => 10));

        $viewParams = array();
        $viewParams['news'] = $news;
        $viewParams['newspapperFiles'] = $newspapperFiles;
        $viewParams['medicaments'] = $medicaments;
        $viewParams['newspaper'] = $newspaper;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function eventListAction()
    {
        $this->layout('layout/home');
        $aboutPage = $this->getPageService()->findOneBySlug('o-nas');
        $text = strip_tags($aboutPage->getContent());
        if(strlen($text) > 230) {
            $text = substr($text, 0, 230) . '..';
        }
        $this->layout()->aboutContent = $text;

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $allEvent = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'event'));

        /* @var $event \CmsIr\Post\Model\Post */

        $page = $this->params()->fromRoute('number') ? (int) $this->params()->fromRoute('number') : 1;
        $allEvent->setCurrentPageNumber($page);
        $allEvent->setItemCountPerPage(2);

        $test = array();

        foreach($allEvent as $event)
        {
            $eventId = $event->getId();
            $eventFiles = $this->getPostFileTable()->getBy(array('post_id' => $eventId));

            $event->setFiles($eventFiles);
            $test[] = $event;

        }

        $viewParams = array();
        $viewParams['events'] = $test;
        $viewParams['paginator'] = $allEvent;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewEventAction()
    {
        $this->layout('layout/home');
        $aboutPage = $this->getPageService()->findOneBySlug('o-nas');
        $text = strip_tags($aboutPage->getContent());
        if(strlen($text) > 230) {
            $text = substr($text, 0, 230) . '..';
        }
        $this->layout()->aboutContent = $text;

        $slug = $this->params('slug');

        /* @var $event \CmsIr\Post\Model\Post */
        $event = $this->getPostTable()->getOneBy(array('url' => $slug));
        $eventId = $event->getId();
        $eventFiles = $this->getPostFileTable()->getBy(array('post_id' => $eventId));

        $event->setFiles($eventFiles);

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $allNews = $this->getPostTable()->getBy(array('status_id' => $activeStatusId, 'category' => 'news'), 'date DESC');

        /* @var $news \CmsIr\Post\Model\Post */

        foreach($allNews as $news)
        {
            $newsId = $news->getId();
            $newsFiles = $this->getPostFileTable()->getBy(array('post_id' => $newsId));

            $news->setFiles($newsFiles);
        }

        $viewParams = array();
        $viewParams['event'] = $event;
        $viewParams['news'] = array_values($allNews);
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function viewPlanAction()
    {
        $this->layout('layout/home');
        $aboutPage = $this->getPageService()->findOneBySlug('o-nas');
        $text = strip_tags($aboutPage->getContent());
        if(strlen($text) > 230) {
            $text = substr($text, 0, 230) . '..';
        }
        $this->layout()->aboutContent = $text;

        $slug = $this->params('slug');

        /* @var $plan \CmsIr\Post\Model\Post */
        $plan = $this->getPostTable()->getOneBy(array('url' => $slug));
        $planId = $plan->getId();
        $planFiles = $this->getPostFileTable()->getBy(array('post_id' => $planId));
        $plan->setFiles($planFiles);

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $allEvent = $this->getPostTable()->getBy(array('status_id' => $activeStatusId, 'category' => 'event'), 'date DESC');

        /* @var $event \CmsIr\Post\Model\Post */

        foreach($allEvent as $event)
        {
            $eventsId = $event->getId();
            $eventFiles = $this->getPostFileTable()->getBy(array('post_id' => $eventsId));

            $event->setFiles($eventFiles);
        }

        $viewParams = array();
        $viewParams['plan'] = $plan;
        $viewParams['events'] = array_values($allEvent);
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function saveSubscriberAjaxAction ()
    {
        $request = $this->getRequest();


        if ($request->isPost()) {
            $uncofimerdStatus = $this->getStatusTable()->getOneBy(array('slug' => 'unconfirmed'));
            $uncofimerdStatusId = $uncofimerdStatus->getId();

            $email = $request->getPost('email');
            $confirmationCode = uniqid();
            $subscriber = new Subscriber();
            $subscriber->setEmail($email);
            $subscriber->setGroups(array());
            $subscriber->setConfirmationCode($confirmationCode);
            $subscriber->setStatusId($uncofimerdStatusId);

            $this->getSubscriberTable()->save($subscriber);
            $this->sendConfirmationEmail($email, $confirmationCode);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }

    public function sendConfirmationEmail($email, $confirmationCode)
    {
        $transport = $this->getServiceLocator()->get('mail.transport')->findMailConfig();
        $from = $this->getServiceLocator()->get('mail.transport')->findFromMail();

        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($email)
            ->addFrom($from)
            ->setEncoding('UTF-8')
            ->setSubject('Prosimy o potwierdzenie subskrypcji!')
            ->setBody("W celu potwierdzenia subskrypcji kliknij w link => " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                $this->url()->fromRoute('newsletter-confirmation', array('code' => $confirmationCode)));
        $transport->send($message);
    }

    public function confirmationNewsletterAction()
    {
        $this->layout('layout/home');
//        $aboutPage = $this->getPageService()->findOneBySlug('o-nas');
//        $text = strip_tags($aboutPage->getContent());
//        if(strlen($text) > 230) {
//            $text = substr($text, 0, 230) . '..';
//        }
//        $this->layout()->aboutContent = $text;

        $request = $this->getRequest();
        $code = $this->params()->fromRoute('code');
        if (!$code) {
            return $this->redirect()->toRoute('home');
        }

        $viewParams = array();

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $events = $this->getPostTable()->getBy(array('status_id' => $activeStatusId, 'category' => 'event'));
        foreach($events as $event)
        {
            $eventFiles = $this->getPostFileTable()->getOneBy(array('post_id' => $event->getId()));
            $event->setFiles($eventFiles);
        }
        $viewParams['banners'] = $events;


        $viewModel = new ViewModel();

        $subscriber = $this->getSubscriberTable()->getOneBy(array('confirmation_code' => $code));

        $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
        $confirmedStatusId = $confirmedStatus->getId();

        if($subscriber == false)
        {
            $viewParams['message'] = 'Nie istnieje taki użytkownik';
            $viewModel->setVariables($viewParams);
            return $viewModel;
        }

        $subscriberStatus = $subscriber->getStatusId();

        if($subscriberStatus == $confirmedStatusId)
        {
            $viewParams['message'] = 'Użytkownik już potwierdził subskrypcję';
        }

        else
        {
            $viewParams['message'] = 'Subskrypcja została potwierdzona';
            $subscriberGroups = $subscriber->getGroups();
            $groups = unserialize($subscriberGroups);

            $subscriber->setStatusId($confirmedStatusId);
            $subscriber->setGroups($groups);
            $this->getSubscriberTable()->save($subscriber);
        }

        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function contactFormAction()
    {
        $request = $this->getRequest();


        if ($request->isPost()) {
            $name = $request->getPost('name');
            $surname = $request->getPost('surname');
            $email = $request->getPost('email');
            $text = $request->getPost('text');
            $phone = $request->getPost('phone');

            $htmlMarkup = "Imię i Nazwisko: " . $name .  ' ' . $surname . "<br>" .
                "Telefon: " . $phone . "<br>" .
                "Email: " . $email . "<br>" .
                "Treść: " . $text;

            $html = new MimePart($htmlMarkup);
            $html->type = "text/html";
            $html->charset = 'utf-8';

            $body = new MimeMessage();
            $body->setParts(array($html));

            $transport = $this->getServiceLocator()->get('mail.transport')->findMailConfig();
            $from = $this->getServiceLocator()->get('mail.transport')->findFromMail();

            $message = new Message();
            $this->getRequest()->getServer();
            $message->addTo('biuro@web-ir.pl')
                ->addFrom($from)
                ->setEncoding('UTF-8')
                ->setSubject('Wiadomość z formularza kontaktowego')
                ->setBody($body);
            $transport->send($message);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }
    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Slider\Service\SliderService
     */
    public function getSliderService()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Service\SliderService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Service\PageService');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostFileTable
     */
    public function getPostFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostFileTable');
    }

    /**
     * @return \Pharmacy\Model\PharmacyTable
     */
    public function getPharmacyTable()
    {
        return $this->getServiceLocator()->get('Pharmacy\Model\PharmacyTable');
    }

    /**
     * @return \CmsIr\Users\Service\UsersService
     */
    public function getUsersService()
    {
        return $this->getServiceLocator()->get('CmsIr\Users\Service\UsersService');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \Medicaments\Model\MedicamentsTable
     */
    public function getMedicamentsTable()
    {
        return $this->getServiceLocator()->get('Medicaments\Model\MedicamentsTable');
    }

    /**
     * @return \CmsIr\Place\Model\PlaceTable
     */
    public function getPlaceTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Place\Model\PlaceTable');
    }
}
