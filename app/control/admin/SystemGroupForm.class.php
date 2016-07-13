<?php
/**
 * System_groupForm Registration
 * @author  <your name here>
 */
class SystemGroupForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the table container
        $table = new TTable;
        $table->style = 'width:100%';
        
        $frame_programs = new TFrame;
        $frame_programs->setLegend(_t('Programs'));
        
        // creates the form
        $this->form = new TForm('form_System_group');
        $this->form->class = 'tform';
        
        
        // add the notebook inside the form
        $this->form->add($table);
        $table->addRowSet( new TLabel(_t('Group')), '' )->class = 'tformtitle';

        // create the form fields
        $id              = new TEntry('id');
        $name            = new TEntry('name');
        $multifield      = new TMultiField('programs');
        $program_id      = new TDBSeekButton('program_id', 'permission', 'form_System_group', 'SystemProgram', 'name', 'programs_id', 'programs_name');
        $program_name    = new TEntry('program_name');
        
        $frame_programs->add($multifield);    
        
        $multifield->setHeight(200);
        $multifield->setClass('SystemProgram');
        $multifield->addField('id', ' ID',  $program_id, 80, true);
        $multifield->addField('name',_t('Name'), $program_name, 350);
        $multifield->setOrientation('horizontal');
        
        // define the sizes
        $program_id->setSize(70);
        $id->setSize(100);
        $name->setSize(200);

        // validations
        $name->addValidation('name', new TRequiredValidator);
        
        // outras propriedades
        $id->setEditable(false);
        $program_name->setEditable(false);

        // add a row for the field id
        $table->addRowSet(new TLabel('ID:'), $id);
        $table->addRowSet(new TLabel(_t('Name') . ': '), $name);
        
        // add a row for the field name
        $row = $table->addRow();
        $cell = $row->addCell($frame_programs);
        $cell->colspan = 2;

        // create an action button (save)
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
        $save_button->setImage('fa:floppy-o');
        
        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onEdit')), _t('New'));
        $new_button->setImage('fa:plus-square green');
        
        $list_button=new TButton('list');
        $list_button->setAction(new TAction(array('SystemGroupList','onReload')), _t('Back to the listing'));
        $list_button->setImage('fa:table blue');

        // define the form fields
        $this->form->setFields(array($id,$name,$multifield,$save_button,$new_button,$list_button));
        
        $buttons = new THBox;
        $buttons->add($save_button);
        $buttons->add($new_button);
        $buttons->add($list_button);
        
        $container = new TTable;
        $container->width = '80%';
        $container->addRow()->addCell(new TXMLBreadCrumb('menu.xml', 'SystemGroupList'));
        $container->addRow()->addCell($this->form);
        
        $row=$table->addRow();
        $row->class = 'tformaction';
        $cell = $row->addCell( $buttons );
        $cell->colspan = 2;

        // add the form to the page
        parent::add($container);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            // get the form data into an active record System_group
            $object = $this->form->getData('SystemGroup');
            
            $this->form->validate(); // form validation
            $object->store(); // stores the object
            $object->clearParts();
            if( $object->programs )
            {
                foreach( $object->programs as $program )
                {
                    $object->addSystemProgram( $program );
                }
            }
            
            $this->form->setData($object); // fill the form with the active record data
            
            TTransaction::close(); // close the transaction
            new TMessage('info', _t('Record saved')); // shows the success message
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('permission');
                
                // instantiates object System_group
                $object = new SystemGroup($key);
                
                $object->programs = $object->getSystemPrograms();
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
