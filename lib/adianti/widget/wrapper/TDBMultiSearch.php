<?php
namespace Adianti\Widget\Wrapper;

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Form\TMultiSearch;
use Adianti\Database\TCriteria;
use Adianti\Widget\Form\TForm;

use Exception;

/**
 * Database Multisearch Widget
 *
 * @version    2.0
 * @package    widget
 * @subpackage wrapper
 * @author     Pablo Dall'Oglio
 * @author     Matheus Agnes Dias
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TDBMultiSearch extends TMultiSearch
{
    protected $id;
    protected $initialItems;
    protected $items;
    protected $size;
    protected $height;
    protected $minLength;
    protected $maxSize;
    protected $database;
    protected $model;
    protected $key;
    protected $column;
    protected $operator;
    protected $orderColumn;
    protected $criteria;
    protected $mask;
    protected $service;
    protected $seed;
    
    /**
     * Class Constructor
     * @param  $name     widget's name
     * @param  $database database name
     * @param  $model    model class name
     * @param  $key      table field to be used as key in the combo
     * @param  $value    table field to be listed in the combo
     * @param  $ordercolumn column to order the fields (optional)
     * @param  $criteria criteria (TCriteria object) to filter the model (optional)
     */
    public function __construct($name, $database, $model, $key, $value, $orderColumn = NULL, TCriteria $criteria = NULL)
    {
        // executes the parent class constructor
        parent::__construct($name);
        
        if (empty($database))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'database', __CLASS__));
        }
        
        if (empty($model))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'model', __CLASS__));
        }
        
        if (empty($key))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'key', __CLASS__));
        }
        
        if (empty($value))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'value', __CLASS__));
        }
        
        $this->database = $database;
        $this->model = $model;
        $this->key = $key;
        $this->column = $value;
        $this->operator = 'like';
        $this->orderColumn = isset($orderColumn) ? $orderColumn : NULL;
        $this->criteria = $criteria;
        $this->mask = '{'.$value.'}';
        $this->service = 'AdiantiMultiSearchService';
        $this->seed = APPLICATION_NAME.'s8dkld83kf73kf094';
    }
    
    /**
     * Define the search service
     * @param $service Search service
     */
    public function setService($service)
    {
        $this->service = $service;
    }
    
    /**
     * Define the search operator
     * @param $operator Search operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }
    
    /**
     * Define the display mask
     * @param $mask Show mask
     */
    public function setMask($mask)
    {
        $this->mask = $mask;
    }
    
    /**
     * Shows the widget
     */
    public function show()
    {
        // define the tag properties
        $this->tag-> name  = $this->name;    // tag name
        $this->tag-> id  = $this->id;    // tag name
        $this->setProperty('style', "width:{$this->size}px", FALSE); //aggregate style info
        $multiple = $this->maxSize == 1 ? 'false' : 'true';
        
        $load_items = 'undefined';
        if ($this->initialItems)
        {
            $new_items = array();
            foreach ($this->initialItems as $key => $item)
            {
                $new_item = array('id' => $key, 'text' => $item);
                $new_items[] = $new_item;
            }
            
            if ($multiple == 'true')
            {
                $load_items = json_encode($new_items);
            }
            else
            {
                $load_items = json_encode($new_item);
            }
        }
        
        $orderColumn = isset($this->orderColumn) ? $this->orderColumn : $this->column;
        $criteria = '';
        if ($this->criteria)
        {
            $criteria = str_replace(array('+', '/'), array('-', '_'), base64_encode(serialize($this->criteria)));
        }
        
        $hash = md5("{$this->seed}{$this->database}{$this->key}{$this->column}{$this->model}");
        $length = $this->minLength;
        
        $class = $this->service;
        $callback = array($class, 'onSearch');
        $method = $callback[1];
        
        $search_word = AdiantiCoreTranslator::translate('Search');
        $url = "engine.php?class={$class}&method={$method}&static=1&database={$this->database}&key={$this->key}&column={$this->column}&model={$this->model}&orderColumn={$orderColumn}&criteria={$criteria}&operator={$this->operator}&mask={$this->mask}";
        
        $change_action = 'function() {}';
        if (isset($this->changeAction))
        {
            if (!TForm::getFormByName($this->formName) instanceof TForm)
            {
                throw new Exception(AdiantiCoreTranslator::translate('You must pass the ^1 (^2) as a parameter to ^3', __CLASS__, $this->name, 'TForm::setFields()') );
            }
            
            $string_action = $this->changeAction->serialize(FALSE);
            $change_action = "function() { serialform=tmultisearch_get_form_data('{$this->formName}', '{$this->name}');
                                         __adianti_ajax_lookup('$string_action&'+serialform, this); }";
        }
        
        TScript::create(" tdbmultisearch_start( '{$this->id}', '{$length}', '{$this->maxSize}', '{$search_word}', $multiple, '{$url}', '{$this->size}px', '{$this->height}px', {$load_items}, '{$hash}', {$change_action} ); ");
        
        // shows the component
        $this->tag->show();
    }
}
