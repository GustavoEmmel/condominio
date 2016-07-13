<?php
namespace Adianti\Widget\Container;

use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TScript;

/**
 * Expander Widget
 *
 * @version    2.0
 * @package    widget
 * @subpackage container
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TExpander extends TElement
{
    private $container;
    private $button;
    
    /**
     * Class Constructor
     * @param  $value text label
     */
    public function __construct($label = '')
    {
        parent::__construct('div');
        $this->{'id'}    = 'texpander_'.mt_rand(1000000000, 1999999999);
        $this->{'class'} = 'dropdown';
        
        $this->button = new TElement('button');
        $this->button->{'class'} = 'btn btn-default dropdown-toggle';
        $this->button->{'type'} = 'button';
        $this->button->{'id'}   = 'button_'.mt_rand(1000000000, 1999999999);
        $this->button->{'data-toggle'} = 'dropdown';
        $this->button->add($label);
        
        $this->container = new TElement('ul');
        $this->container->{'class'} = 'dropdown-menu texpander-container';
        $this->container->{'style'} = 'z-index: inherit';
        
        $this->container->{'role'} = 'menu';
        $this->container->{'aria-labelledby'} = $this->button->{'id'};
        
        parent::add($this->button);
        parent::add($this->container);
    }
    
    /**
     * Define a button property
     * @param $property Property name (Ex: style)
     * @param $value    Property value
     */
    public function setButtonProperty($property, $value)
    {
        $this->button->$property = $value;
    }
    
    /**
     * Define a container property
     * @param $property Property name (Ex: style)
     * @param $value    Property value
     */
    public function setProperty($property, $value)
    {
        $this->container->$property = $value;
    }
    
    /**
     * Add content to the expander
     * @param $content Any Object that implements show() method
     */
    public function add($content)
    {
        $this->container->add($content);
    }
    
    /**
     * Shows the expander
     */
    public function show()
    {
        parent::show();
        TScript::create('texpander_start();');
    }
}
