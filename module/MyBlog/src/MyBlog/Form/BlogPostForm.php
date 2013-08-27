<?php
namespace MyBlog\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class BlogPostForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('blogpost');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new \MyBlog\Form\BlogPostInputFilter());
        $this->add(array(
            'name' => 'security',
            'type' => 'Zend\Form\Element\Csrf',
        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'created',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'userId',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title',
            ),
            'options' => array(
                'min' => 3,
                'max' => 25
            ),
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Text',
            ),
        ));
        $this->add(array(
            'name' => 'state',
            'type' => 'Checkbox',
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }
}
