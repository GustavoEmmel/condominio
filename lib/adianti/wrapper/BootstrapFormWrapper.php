<?php
namespace Adianti\Wrapper;

use Adianti\Widget\Wrapper\TQuickForm;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Form\TButton;

/**
 * Bootstrap form decorator for Adianti Framework
 *
 * @version    2.0
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 * @wrapper    TQuickForm
 */
class BootstrapFormWrapper
{
    private $decorated;
    private $currentGroup;
    private $element;
    
    /**
     * Constructor method
     */
    public function __construct(TQuickForm $form, $class = 'form-horizontal')
    {
        $this->decorated = $form;
        
        $this->element   = new TElement('form');
        $this->element->{'class'}   = $class;
        $this->element->{'enctype'} = "multipart/form-data";
        $this->element->{'method'}  = 'post';
        $this->element->{'name'}    = $this->decorated->getName();
        $this->element->{'id'}      = $this->decorated->getName();
    }
    
    /**
     * Redirect calls to decorated object
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method),$parameters);
    }
    
    /**
     * Redirect assigns to decorated object
     */
    public function __set($property, $value)
    {
        return $this->element->$property = $value;
    }
    
    /**
     * Shows the decorated form
     */
    public function show()
    {
        $fieldsByRow = $this->decorated->getFieldsByRow();
        $classWidth  = array(1=>array(3,9), 2=>array(2,4), 3=>array(2,2));
        $labelClass  = $classWidth[$fieldsByRow][0];
        $fieldClass  = $classWidth[$fieldsByRow][1];
        $fieldCount  = 0;
        
        foreach ($this->decorated->getFields() as $field)
        {
            if (!$field instanceof TButton)
            {
                if ( empty($this->currentGroup) OR ( $fieldCount % $fieldsByRow ) == 0 )
                {
                    // add the field to the container
                    $this->currentGroup = new TElement('div');
                    $this->currentGroup->{'class'} = 'form-group';
                    $this->element->add($this->currentGroup);
                }
                $group = $this->currentGroup;
                
                $label = new TElement('label');
                
                if ($this->element->{'class'} == 'form-inline')
                {
                    $label->{'style'} = 'padding-left: 3px; font-weight: bold';
                }
                else
                {
                    $label->{'style'} = 'font-weight: bold; margin-bottom: 3px';
                    if ($this->element->{'class'} == 'form-horizontal')
                    {
                        $label->{'class'} = 'col-sm-'.$labelClass.' control-label';
                    }
                    else
                    {
                        $label->{'class'} = ' control-label';
                    }
                }
                
                $label->add($field->getLabel());
                $group->add($label);
                
                if ($this->element->{'class'} == 'form-inline')
                {
                    $group->add($field);
                }
                else
                {
                    $col = new TElement('div');
                    
                    if ($this->element->{'class'} == 'form-horizontal')
                    {
                        $col->{'class'} = 'col-sm-'.$fieldClass;
                    }
                    $col->add($field);
                    $group->add($col);
                }
                
                if ($this->element->{'class'} !== 'form-inline')
                {
                    if ($this->element->{'class'} == 'form-horizontal')
                    {
                        $field->{'class'} = 'form-control input-sm '.$field->{'class'};
                        $field->{'style'} = $field->style . ';float:left';
                    }
                    else
                    {
                        $field->{'class'} = 'form-control '.$field->{'class'};
                        $field->{'style'} = $field->style . ';display:inline-block';
                    }
                }
                
                $fieldCount ++;
            }
        }
        
        if ($this->decorated->getActionButtons())
        {
            $group = new TElement('div');
            $group->{'class'} = 'form-group';
            $col = new TElement('div');
            $col->{'class'} = 'col-sm-offset-'.$labelClass.' col-sm-'.$fieldClass;
            
            $i = 0;
            foreach ($this->decorated->getActionButtons() as $action)
            {
                $col->add($action);
                $i ++;
            }
            $group->add($col);
            $this->element->add($group);
        }
        
        $this->element->{'width'} = '100%';
        $this->element->show();
    }
}
