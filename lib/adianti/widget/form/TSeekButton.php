<?php
namespace Adianti\Widget\Form;

use Adianti\Widget\Form\AdiantiWidgetInterface;
use Adianti\Control\TAction;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TField;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Util\TImage;

use Adianti\Core\AdiantiCoreTranslator;
use Exception;
use ReflectionClass;

/**
 * Record Lookup Widget: Creates a lookup field used to search values from associated entities
 *
 * @version    2.0
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TSeekButton extends TEntry implements AdiantiWidgetInterface
{
    private $action;
    private $auxiliar;
    private $useOutEvent;
    private $button;
    protected $formName;
    protected $name;
    
    /**
     * Class Constructor
     * @param  $name name of the field
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->useOutEvent = TRUE;
        $this->setProperty('class', 'tfield tseekentry', TRUE);   // classe CSS
        $image = new TImage('lib/adianti/images/ico_find.png');
        
        $this->button = new TElement('button');
        $this->button->{'class'} = 'btn btn-default tseekbutton';
        $this->button->{'type'} = 'button';
        $this->button->{'onmouseover'} = 'style.cursor = \'pointer\'';
        $this->button->{'name'} = '_' . $this->name . '_link';
        $this->button->{'onmouseout'}  = 'style.cursor = \'default\'';
        $this->button->add($image);
    }
    
    /**
     * Returns a property value
     * @param $name     Property Name
     */
    public function __get($name)
    {
        if ($name == 'button')
        {
            return $this->button;
        }
        else
        {
            return parent::__get($name);
        }
    }
    
    /**
     * Define it the out event will be fired
     */
    public function setUseOutEvent($bool)
    {
        $this->useOutEvent = $bool;
    }
    
    /**
     * Define the action for the SeekButton
     * @param $action Action taken when the user
     * clicks over the Seek Button (A TAction object)
     */
    public function setAction(TAction $action)
    {
        $this->action = $action;
    }
    
    /**
     * Define an auxiliar field
     * @param $object any TField object
     */
    public function setAuxiliar(TField $object)
    {
        $this->auxiliar = $object;
    }
    
    /**
     * Enable the field
     * @param $form_name Form name
     * @param $field Field name
     */
    public static function enableField($form_name, $field)
    {
        TScript::create( " tseekbutton_enable_field('{$form_name}', '{$field}'); " );
    }
    
    /**
     * Disable the field
     * @param $form_name Form name
     * @param $field Field name
     */
    public static function disableField($form_name, $field)
    {
        TScript::create( " tseekbutton_disable_field('{$form_name}', '{$field}'); " );
    }
    
    /**
     * Show the widget
     */
    public function show()
    {
        // check if it's not editable
        if (parent::getEditable())
        {
            if (!TForm::getFormByName($this->formName) instanceof TForm)
            {
                throw new Exception(AdiantiCoreTranslator::translate('You must pass the ^1 (^2) as a parameter to ^3', __CLASS__, $this->name, 'TForm::setFields()') );
            }
            
            $serialized_action = '';
            if ($this->action)
            {
                // get the action class name
                if (is_array($callback = $this->action->getAction()))
                {
                    if (is_object($callback[0]))
                    {
                        $rc = new ReflectionClass($callback[0]);
                        $classname = $rc->getShortName();
                    }
                    else
                    {
                        $classname  = $callback[0];
                    }
                    
                    $inst       = new $classname;
                    $ajaxAction = new TAction(array($inst, 'onSelect'));
                    
                    if (in_array($classname, array('TStandardSeek')))
                    {
                        $ajaxAction->setParameter('parent',  $this->action->getParameter('parent'));
                        $ajaxAction->setParameter('database',$this->action->getParameter('database'));
                        $ajaxAction->setParameter('model',   $this->action->getParameter('model'));
                        $ajaxAction->setParameter('display_field', $this->action->getParameter('display_field'));
                        $ajaxAction->setParameter('receive_key',   $this->action->getParameter('receive_key'));
                        $ajaxAction->setParameter('receive_field', $this->action->getParameter('receive_field'));
                        $ajaxAction->setParameter('criteria',      $this->action->getParameter('criteria'));
                    }
                    else
                    {
                    	if($actionParameters = $this->action->getParameters())
                    	{
	                    	foreach ($actionParameters as $key => $value) 
	                    	{
	                    		$ajaxAction->setParameter($key, $value);
	                    	}                    		
                    	}                    	                    
                    }
                    $ajaxAction->setParameter('form_name', $this->formName);
                    $string_action = $ajaxAction->serialize(FALSE);
                    if ($this->useOutEvent)
                    {
                        $this->setProperty('seekaction', "__adianti_post_lookup('{$this->formName}', '{$string_action}', document.{$this->formName}.{$this->name})");
                        $this->setProperty('onBlur', $this->getProperty('seekaction'), FALSE);
                    }
                }
                $this->action->setParameter('form_name', $this->formName);
                $serialized_action = $this->action->serialize(FALSE);
            }
            parent::show();
            
            $this->button-> onclick = "javascript:serialform=(\$('#{$this->formName}').serialize());
                  __adianti_append_page('engine.php?{$serialized_action}&'+serialform)";
            
            $this->button->show();
            
            if ($this->auxiliar)
            {
                echo '&nbsp;';
                $this->auxiliar->show();
            }
        }
        else
        {
            parent::show();
        }
    }
}
