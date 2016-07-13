<?php
namespace Adianti\Base;

use Adianti\Core\AdiantiCoreTranslator;
use Exception;

trait AdiantiStandardControlTrait
{
    protected $database; // Database name
    protected $activeRecord;    // Active Record class name
    
    /**
     * method setDatabase()
     * Define the database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }
    
    /**
     * method setActiveRecord()
     * Define wich Active Record class will be used
     */
    public function setActiveRecord($activeRecord)
    {
        if (is_subclass_of($activeRecord, 'TRecord'))
        {
            $this->activeRecord = $activeRecord;
        }
        else
        {
            throw new Exception(AdiantiCoreTranslator::translate('The class ^1 must be subclass of ^2', $activeRecord, 'TRecord'));
        }
    }
}
