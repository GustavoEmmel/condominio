<?php
namespace Adianti\Wrapper;
use Adianti\Widget\Container\TNotebook;

/**
 * Bootstrap datagrid decorator for Adianti Framework
 *
 * @version    2.0
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 * @wrapper    TNotebook
 */
class BootstrapNotebookWrapper
{
    private $decorated;
    
    /**
     * Constructor method
     */
    public function __construct(TNotebook $notebook)
    {
        $this->decorated = $notebook;
    }
    
    /**
     * Redirect calls to decorated object
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method),$parameters);
    }
    
    /**
     * Shows the decorated datagrid
     */
    public function show()
    {
        $rendered = $this->decorated->render();
        $rendered->{'role'} = 'tabpanel';
        unset($rendered->{'class'});
        
        $sessions = $rendered->getChildren();
        if ($sessions)
        {
            foreach ($sessions as $section)
            {
                if ($section->{'class'} == 'tabs')
                {
                    $section->{'class'} = "nav nav-tabs";
                    $section->{'role'}  = "tablist";
                }
                if ($section->{'class'} == 'spacer')
                {
                    $section->{'style'} = "display:none";
                }
            }
        }
        $rendered->show();
    }
}
