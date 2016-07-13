<?php
namespace Adianti\Widget\Form;

use Adianti\Widget\Form\AdiantiWidgetInterface;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Form\TField;
use Adianti\Control\TAction;
use Adianti\Core\AdiantiCoreTranslator;
use Exception;

/**
 * Entry Widget
 *
 * @version    2.0
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TEntry extends TField implements AdiantiWidgetInterface
{
    private $mask;
    private $completion;
    private $exitAction;
    private $numericMask;
    private $decimals;
    private $decimalsSeparator;
    private $thousandSeparator;
    private $replaceOnPost;
    protected $id;
    protected $formName;
    protected $name;
    
    /**
     * Class Constructor
     * @param  $name name of the field
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->numericMask = FALSE;
        $this->replaceOnPost = FALSE;
    }
    
    /**
     * Define the field's mask
     * @param $mask A mask for input data
     */
    public function setMask($mask)
    {
        $this->mask = $mask;
    }
    
    /**
     * Define the field's numeric mask (available just in web)
     * @param $decimals Sets the number of decimal points.
     * @param $decimalsSeparator Sets the separator for the decimal point.
     * @param $thousandSeparator Sets the thousands separator.
     */
    public function setNumericMask($decimals, $decimalsSeparator, $thousandSeparator, $replaceOnPost = FALSE)
    {
        $this->numericMask = TRUE;
        $this->decimals = $decimals;
        $this->decimalsSeparator = $decimalsSeparator;
        $this->thousandSeparator = $thousandSeparator;
        $this->replaceOnPost = $replaceOnPost;
    }
    
    /**
     * Define the field's value
     * @param $value A string containing the field's value
     */
    public function setValue($value)
    {
        if ($this->replaceOnPost)
        {
            $this->value = number_format($value, $this->decimals, $this->decimalsSeparator, $this->thousandSeparator);
        }
        else
        {
            $this->value = $value;
        }
    }
    
    /**
     * Return the post data
     */
    public function getPostData()
    {
        if (isset($_POST[$this->name]))
        {
            if ($this->replaceOnPost)
            {
                $value = $_POST[$this->name];
                $value = str_replace( $this->thousandSeparator, '', $value);
                $value = str_replace( $this->decimalsSeparator, '.', $value);
                return $value;
            }
            else
            {
                return $_POST[$this->name];
            }
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Define max length
     * @param  $length Max length
     */
    public function setMaxLength($length)
    {
        if ($length > 0)
        {
            $this->tag-> maxlength = $length;
        }
    }
    
    /**
     * Define options for completion
     * @param $options array of options for completion
     */
    function setCompletion($options)
    {
        $this->completion = $options;
    }
    
    /**
     * Define the action to be executed when the user leaves the form field
     * @param $action TAction object
     */
    function setExitAction(TAction $action)
    {
        if ($action->isStatic())
        {
            $this->exitAction = $action;
        }
        else
        {
            $string_action = $action->toString();
            throw new Exception(AdiantiCoreTranslator::translate('Action (^1) must be static to be used in ^2', $string_action, __METHOD__));
        }
    }
    
    /**
     * Shows the widget at the screen
     */
    public function show()
    {
        // define the tag properties
        $this->tag-> name  = $this->name;    // TAG name
        $this->tag-> value = $this->value;   // TAG value
        $this->tag-> type  = 'text';         // input type
        
        if (strstr($this->size, '%') !== FALSE)
        {
            $this->setProperty('style', "width:{$this->size};", false); //aggregate style info
        }
        else
        {
            $this->setProperty('style', "width:{$this->size}px;", false); //aggregate style info
        }
        
        if ($this->id)
        {
            $this->tag->{'id'} = $this->id;
        }
        
        // verify if the widget is non-editable
        if (parent::getEditable())
        {
            if (isset($this->exitAction))
            {
                if (!TForm::getFormByName($this->formName) instanceof TForm)
                {
                    throw new Exception(AdiantiCoreTranslator::translate('You must pass the ^1 (^2) as a parameter to ^3', __CLASS__, $this->name, 'TForm::setFields()') );
                }
                $string_action = $this->exitAction->serialize(FALSE);

                $this->setProperty('exitaction', "__adianti_post_lookup('{$this->formName}', '{$string_action}', document.{$this->formName}.{$this->name})");
                $this->setProperty('onBlur', $this->getProperty('exitaction'), FALSE);
            }
            
            if ($this->mask)
            {
                $this->tag-> onKeyPress="return tentry_mask(this,event,'{$this->mask}')";
            }
        }
        else
        {
            $this->tag-> readonly = "1";
            $this->tag->{'class'} = 'tfield_disabled'; // CSS
            $this->tag-> onmouseover = "style.cursor='default'";
        }
        
        // shows the tag
        $this->tag->show();
        
        if (isset($this->completion))
        {
            $options = json_encode($this->completion);
            TScript::create(" tentry_autocomplete( '{$this->name}', $options); ");
        }
        if ($this->numericMask)
        {
            TScript::create( "tentry_numeric_mask( '{$this->name}', {$this->decimals}, '{$this->decimalsSeparator}', '{$this->thousandSeparator}'); ");
        }
    }
}
