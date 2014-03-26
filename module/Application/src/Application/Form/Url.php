<?php
namespace Application\Form;

use Zend\Form\Form;

class Url extends Form
{
    public function __construct($name = 'form-url', $options = array())
    {
        parent::__construct($name, $options);
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'required' => true
        ));
        $this->add(array(
            'name' => 'url',
            'type' => 'Text',
            'required' => true
        ));
        $this->add(array(
            'name' => 'note',
            'type' => 'TextArea',
            'required' => false
        ));
        $this->add(array(
            'name' => 'tags',
            'type' => 'Text',
            'required' => true
        ));
    }
    
    public function injectData(\ArrayObject $data)
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'value' => $data->id
            )
        ));
        
        $this->get('url')
             ->setValue($data->url);
             
        $this->get('title')
             ->setValue($data->title);
             
        $this->get('note')
             ->setValue($data->note);
        
        return $this;
    }
    
    public function injectTags(array $data)
    {
        $tags  = array_map( function($tagName) {
                        return $tagName['name']; 
                    }, $data
                 );
                 
        $this->get('tags')
             ->setValue(implode(', ', $tags));
        
        return $this;
    }
}
