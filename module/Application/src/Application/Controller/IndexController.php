<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\Url as UrlService;

class IndexController extends AbstractActionController
{
    /**
     * @var UrlService
     */
    protected $urlService;
    
    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }
    
    public function indexAction()
    {
        $page = $this->getEvent()
                     ->getRouteMatch()
                     ->getParam('page');
    
        $allUrl     = $this->urlService
                           ->getUrlMapper()
                           ->fetchAll();
                           
        $paginator  = $this->urlService
                           ->getPaginator($allUrl, $page);
        
        $view = new ViewModel();
        $view->setVariables(array(
            'data' => $paginator
        ));
        
        return $view;
    }
    
    public function addAction()
    {
        $form = $this->urlService
                     ->getForm();
                     
        $form->getInputFilter()
             ->addUniqueUrlValidator();
        
        if($this->getRequest()->isPost()) {
            $postData = $this->getRequest()
                             ->getPost()
                             ->toArray();
            
            if($form->setData($postData)->isValid()) {
                $this->urlService
                     ->save($form->getData());
                
                $this->flashMessenger()
                     ->addMessage('Url baru telah ditambahkan.');
                     
                return $this->redirect()
                            ->toRoute('home');
            }
        }
        
        $view = new ViewModel();
        $view->setVariables(array(
            'form' => $form
        ));
        
        return $view;
    }
    
    public function editAction()
    {
        $id = $this->getEvent()
                   ->getRouteMatch()
                   ->getParam('id');
                   
        $urlExists  = $this->urlService
                           ->getUrlMapper()
                           ->fetchOneById($id);
        
        if(! $urlExists) {
            $this->flashMessenger()
                 ->addMessage('Url tidak ditemukan.');
                 
            return $this->redirect()
                        ->toRoute('home');
        }
        
        $tags = $this->urlService
                     ->getTagMapper()
                     ->fetchAllByUrlId($urlExists->id);
        
        $form = $this->urlService
                     ->getForm();
                     
        $form->injectData($urlExists)
             ->injectTags($tags->toArray());
             
        $form->getInputFilter()
             ->addExistingUrlValidator();
        
        if($this->getRequest()->isPost()) {
            $postData = $this->getRequest()
                             ->getPost()
                             ->toArray();
            
            if($form->setData($postData)->isValid()) {
                $this->urlService
                     ->save($form->getData());
                
                $this->flashMessenger()
                     ->addMessage('Url telah di-update.');
                
                return $this->redirect()
                            ->toRoute('home');
            }
        }
        
        $view = new ViewModel();
        $view->setVariables(array(
            'form'      => $form,
            'data'      => $urlExists,
            'readonly'  => true // cara simpel untuk memastikan url tidak berubah (html5)
        ));
        
        return $view;
    }
    
    public function deleteAction()
    {
        $id = $this->getEvent()
                   ->getRouteMatch()
                   ->getParam('id');
                   
        $token = $this->getEvent()
                      ->getRouteMatch()
                      ->getParam('token');
        
        if($token !== (md5($id . date('YHdHm'))) ) {
            $this->flashMessenger()
                 ->addErrorMessage('Token tidak valid, data belum bisa dihapus.');
            
            return $this->redirect()
                        ->toRoute('home');
        } else {
            $this->urlService
                 ->delete($id);
                 
            $this->flashMessenger()
                 ->addMessage('Url telah dihapus.');
                 
            return $this->redirect()
                        ->toRoute('home');
        }
    }
    
    public function tagsAction()
    {
        $tags       = $this->urlService
                           ->getTagMapper()
                           ->fetchAll();
    
        $view = new ViewModel();
        $view->setVariables(array(
            'data' => $tags
        ));
        
        return $view;
    }
    
    public function tagAction()
    {
        $page = $this->getEvent()
                     ->getRouteMatch()
                     ->getParam('page');
                   
        $slug = $this->getEvent()
                     ->getRouteMatch()
                     ->getParam('slug');
        
        $slugExists = $this->urlService
                           ->getTagMapper()
                           ->fetchOneBySlug($slug);
        
        if(! is_object($slugExists)) {
            $this->flashMessenger()
                 ->addErrorMessage('Tag tidak ditemukan.');
                 
            return $this->redirect()
                        ->toRoute('home');
        }
        
        $allUrlByTagSlug = $this->urlService
                                ->getUrlMapper()
                                ->fetchAllByTagSlug($slugExists->slug);
        
        $paginator  = $this->urlService
                           ->getPaginator($allUrlByTagSlug, $page);
        
        $view = new ViewModel();
        $view->setVariables(array(
            'data' => $paginator,
            'slug' => $slug
        ));
        
        $view->setTemplate('application/index/index');
        
        return $view;
    }
}
