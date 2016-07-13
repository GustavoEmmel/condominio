<?php
/**
 * SystemProgramForm Registration
 * @author  <your name here>
 */
class SystemProgramForm extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
                
        // creates the form
        
        $this->form = new TQuickForm('form_SystemProgram');
        $this->form->setFormTitle(_t('Program'));
        $this->form->class = 'tform'; // CSS class
        
        // defines the database
        parent::setDatabase('permission');
        
        // defines the active record
        parent::setActiveRecord('SystemProgram');
        
        // create the form fields
        $id            = new TEntry('id');
        $name          = new TEntry('name');
        $controller    = new TEntry('controller');
        
        $id->setEditable(false);

        // add the fields
        $this->form->addQuickField('ID', $id,  50);
        $this->form->addQuickField(_t('Name') . ': ', $name,  200);
        $this->form->addQuickField(_t('Controller') . ': ', $controller,  200);

        // validations
        $name->addValidation(_t('Name'), new TRequiredValidator);
        $controller->addValidation(('Controller'), new TRequiredValidator);

        // add form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'), new TAction(array($this, 'onEdit')), 'fa:plus-square green');
        $this->form->addQuickAction(_t('Back to the listing'),new TAction(array('SystemProgramList','onReload')),'fa:table blue');

        $container = new TTable;
        $container->style = 'width: 80%';
        $container->addRow()->addCell(new TXMLBreadCrumb('menu.xml','SystemProgramList'));
        $container->addRow()->addCell($this->form);
        
        
        // add the form to the page
        parent::add($container);
    }
}
?>