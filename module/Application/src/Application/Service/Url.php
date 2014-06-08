<?php

namespace Application\Service;

use Zend\Db\Adapter\Adapter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter as PaginatorAdapter;
use Zend\Db\Sql;
use Application\Mapper;
use Application\Form;
use Application\InputFilter;

class Url
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter;
    
    /**
     * @var \Application\Mapper\Url
     */
    protected $urlMapper;
    
    /**
     * @var \Application\Mapper\Tag
     */
    protected $tagMapper;
    
    /**
     * @var \Application\Form\Url
     */
    protected $form;
    
    /**
     * @var \Application\InputFilter\Url
     */
    protected $inputFilter;
    
    public function setDbAdapter(Adapter $adapter)
    {
        $this->dbAdapter = $adapter;
        
        return $this;
    }
    
    /**
     * @return  \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }
    
    /**
     * @return  \Application\Mapper\Url
     */
    public function getUrlMapper()
    {
        if( (null === $this->urlMapper) 
                || (! $this->urlMapper instanceof Mapper\Url) ) 
        {
            $this->setUrlMapper(new Mapper\Url($this->getDbAdapter()));
        }
        
        return $this->urlMapper;
    }
    
    public function setUrlMapper(Mapper\Url $urlMapper)
    {
        $this->urlMapper = $urlMapper;
        
        return $this;
    }
    
    /**
     * @return  \Application\Mapper\Tag
     */
    public function getTagMapper()
    {
        if( (null === $this->tagMapper) 
                || (! $this->tagMapper instanceof Mapper\Tag) ) 
        {
            $this->setTagMapper(new Mapper\Tag($this->getDbAdapter()));
        }
        
        return $this->tagMapper;
    }
    
    public function setTagMapper(Mapper\Tag $tagMapper)
    {
        $this->tagMapper = $tagMapper;
        
        return $this;
    }
    
    /**
     * @return  \Application\Form\Url
     */
    public function getForm()
    {
        if( (null === $this->form) 
                || (! $this->form instanceof Form\Url) ) 
        {
            $form = new Form\Url();
            $form->setInputFilter($this->getInputFilter());
            $this->setForm($form);
        }
        
        return $this->form;
    }
    
    public function setForm(Form\Url $form)
    {
        $this->form = $form;
        return $this;
    }
    
    /**
     * @return  \Application\InputFilter\Url
     */
    public function getInputFilter()
    {
        if( (null === $this->inputFilter) 
                || (! $this->inputFilter instanceof InputFilter\Url) ) 
        {
            $this->setInputFilter(new InputFilter\Url($this->getDbAdapter()));
        }
        return $this->inputFilter;
    }
    
    public function setInputFilter(InputFilter\Url $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }
    
    public function save(array $data)
    {
        $tags = $data['tags'];
        
        unset($data['tags']);
        
        $url = $this->getUrlMapper()
                    ->save($data); // return (int) id / last Insert Id
                    
        $this->getTagMapper()
             ->save($url, explode(',', $tags));
    }
    
    public function delete($urlId)
    {
        $this->getTagMapper()
             ->deleteUrlTagByUrlId($urlId);
             
        $this->getUrlMapper()
             ->delete($urlId);
    }
    
    /**
     * @return  \Zend\Paginator\Paginator
     */
    public function getPaginator(Sql\Select $select, $page, $itemCountPerPage = 20)
    {
        $adapter   = new PaginatorAdapter\DbSelect($select, $this->getDbAdapter());
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setItemCountPerPage($itemCountPerPage);
                  
        return $paginator;
    }
}